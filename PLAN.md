# Plan: Optimize the GitHub Actions CI/CD

## Goal

Rework this repository's GitHub Actions so it follows the battle-tested structure
used by the reference project (`reference/.github/workflows`), while keeping the
parts that are specific to this site (SQLite test DB, Docker Hub, `microk8s`).

The reference is not a drop-in copy: it targets a multi-tenant app with Flux,
PostgreSQL, Gotenberg and **self-hosted runners**. This plan ports the *structure
and optimizations* and deliberately drops the parts that do not apply.

---

## Critical constraint: runners

The reference uses `runs-on: self-hosted` everywhere. This repo has **zero
self-hosted runners** (`GET /actions/runners` → `total_count: 0`).

> **Decision:** keep `runs-on: ubuntu-latest`. Switching to `self-hosted` would
> break every job until a runner is provisioned. Every other improvement below
> works on `ubuntu-latest`.
>
> If self-hosted runners are provisioned later, the only change is the
> `runs-on` value in `setup.yml`, the four `test-*.yml` files and `deploy`.

Verified facts used by this plan:

- Repo: `git@github.com:sertxudeveloper/sertxu.dev.git`, default branch `main`.
- No branch protection → no required status checks to migrate.
- Secrets present: `DOCKERHUB_USERNAME`, `DOCKERHUB_TOKEN`, `KUBERNETES_MASTER_SSH_*`.
- Tests are SQLite-based (`.env.example` + `phpunit.xml`), `RefreshDatabase`
  in `tests/Pest.php`. No Postgres, no Gotenberg, no Flux, no private Composer registry.
- `CreateOgImageJobTest` calls `$job->handle()` → `Spatie\Browsershot\Browsershot`,
  so **Chrome is required** in the unit job (the reference does not need this).
- Composer scripts available: `test:lint`, `test:refactor`, `test:type-coverage`,
  `test:unit`. No `test:types` (no Larastan). No `translations:check`.

---

## Current implementation vs reference

| Concern | Current (`sertxu.dev`) | Reference | Verdict |
|---|---|---|---|
| Workflow files | 3 (`tests.yml`, `deploy-to-k8s.yaml`, `dependabot-auto-merge.yml`) | 11 (setup + per-test + orchestrator + deploy + lint + tia) | Adopt setup/per-test split |
| Dependency install | Re-run for every matrix entry | Installed once in `setup`, restored from cache | **Fix (biggest win)** |
| Vendor cache | `actions/cache` on `vendor` only | Composer cache dir + `vendor`, with `fail-on-cache-miss` | Adopt |
| Node/build cache | None reused across jobs | `node_modules` + `public/build` cached and restored | Adopt |
| Coverage tooling | `coverage: xdebug` on **all** jobs | `none` for lint/refactor, `xdebug` only where needed | **Fix** |
| Job timeouts | None | `timeout-minutes` per job | Adopt |
| Concurrency | None | `cancel-in-progress` on PRs | Adopt |
| `[skip tests]` escape hatch | None | Supported | Optional |
| Test logs on failure | None | `upload-artifact` | Adopt |
| Deploy | Single `build-and-deploy` job | Split `build` + `deploy`, both `timeout-minutes` | Adopt |
| Docker build | No app version, no cache refinements | `APP_VERSION` build arg + cache mounts | Optional |
| Runner | `ubuntu-latest` | `self-hosted` | **Keep `ubuntu-latest`** |

---

## Issues in the current implementation

1. **Redundant setup (high impact).** `tests.yml` uses a 4-entry matrix; every
   entry runs its own checkout, PHP/Node setup, `composer install`, `npm ci`,
   `npm run build`, db creation and migrations. `composer install` runs **4×**,
   `npm ci`/build run **2×**. Jobs are independent, so the caches do not help
   within a run.
2. **Wrong coverage driver.** `coverage: xdebug` is set for `lint` and `refactor`,
   which run Pint/Rector and need no coverage. Xdebug slows every PHP process.
3. **Dead work.** `Create Database` + `Run Migrations` against
   `database/database.sqlite` are effectively useless: `phpunit.xml` uses
   `DB_DATABASE=:memory:` and `RefreshDatabase` migrates it per test. The workflow
   only overrides it via env (`DB_DATABASE: database/database.sqlite`), which
   fights `phpunit.xml` and makes the two disagree.
4. **No timeouts.** A hung job blocks the queue indefinitely.
5. **No concurrency.** Pushing twice to a PR queues both full runs.
6. **No failure artifacts.** Laravel logs from failed runs are lost.
7. **Deploy rebuilds on every retry.** `build-and-deploy` is a single job; a
   failed SSH step forces a full image rebuild.
8. `checkout` without `fetch-depth: 0` (needed by some tools / Rector diffs).
9. Chrome install is manually repeated and not cached (`~/.cache/puppeteer`).

---

## Target structure

```
.github/workflows/
├── setup.yml                 # NEW  – install + cache deps & assets (workflow_call)
├── test-lint.yml             # NEW  – composer test:lint        (workflow_call)
├── test-refactor.yml         # NEW  – composer test:refactor    (workflow_call)
├── test-type-coverage.yml    # NEW  – composer test:type-coverage
├── test-unit.yml             # NEW  – composer test:unit + Chrome
├── tests.yml                 # REWRITE – orchestrator, name stays "Tests"
├── deploy-to-k8s.yml         # REWRITE – build + deploy, calls tests.yml (renamed .yaml→.yml, name kept)
└── dependabot-auto-merge.yml # KEEP (verify trigger still matches "Tests")
```

> Keep the workflow **name** `Tests` on `tests.yml` and `Deploy to Kubernetes` on
> the deploy file: Dependabot auto-merge triggers on the workflow name `Tests`,
> and renaming changes the badge/URL. Filename extension change (`.yaml`→`.yml`)
> is cosmetic only.

---

## Detailed changes

### 1. NEW `.github/workflows/setup.yml`

Runs once per pipeline. Installs PHP + Node deps and builds assets, then stores
`vendor`, `node_modules` and `public/build` in caches that the test jobs restore.

```yaml
name: Setup

on:
  workflow_call:

jobs:
  setup:
    name: Install dependencies and build assets
    runs-on: ubuntu-latest
    timeout-minutes: 15
    steps:
      - name: Checkout
        uses: actions/checkout@v7.0.1
        with:
          fetch-depth: 0

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.5'
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, bcmath, soap, intl, gd, exif, iconv, imagick, fileinfo
          tools: composer:v2
          coverage: none

      - name: Setup Node
        uses: actions/setup-node@v7.1.0
        with:
          node-version: '24'
          cache: 'npm'

      - name: Get composer cache directory
        id: composer-cache
        run: echo "dir=$(composer config cache-files-dir)" >> "$GITHUB_OUTPUT"

      - name: Cache Composer packages
        uses: actions/cache@v6
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: ${{ runner.os }}-composer-

      - name: Cache PHP vendor
        id: php-vendor-cache
        uses: actions/cache@v6
        with:
          path: vendor
          key: ${{ runner.os }}-php-vendor-${{ hashFiles('**/composer.lock') }}
          restore-keys: ${{ runner.os }}-php-vendor-

      - name: Install PHP dependencies
        if: steps.php-vendor-cache.outputs.cache-hit != 'true'
        run: composer install --no-interaction --optimize-autoloader --prefer-dist --no-progress

      - name: Cache Node modules
        id: node-modules-cache
        uses: actions/cache@v6
        with:
          path: node_modules
          key: ${{ runner.os }}-node-modules-${{ hashFiles('**/package-lock.json') }}
          restore-keys: ${{ runner.os }}-node-modules-

      - name: Install Node dependencies
        if: steps.node-modules-cache.outputs.cache-hit != 'true'
        run: npm ci

      - name: Build assets
        run: npm run build

      - name: Cache build assets
        uses: actions/cache@v6
        with:
          path: public/build
          key: ${{ runner.os }}-assets-${{ github.sha }}
```

Notes:
- `ext-zip` is required by `composer.json` (`"ext-zip": "*"`); include it.
- `imagick` + `gd` cover Spatie MediaLibrary conversions.
- Build assets are cached by `github.sha` so downstream jobs get the exact build.

### 2. NEW per-test reusable workflows

All four follow the reference template. Example — `test-lint.yml`:

```yaml
name: Lint

on:
  workflow_call:

jobs:
  test-lint:
    name: Test lint
    runs-on: ubuntu-latest
    timeout-minutes: 5
    steps:
      - name: Checkout
        uses: actions/checkout@v7.0.1
        with:
          fetch-depth: 0

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.5'
          tools: composer:v2
          coverage: none

      - name: Restore PHP vendor
        uses: actions/cache@v6
        with:
          path: vendor
          key: ${{ runner.os }}-php-vendor-${{ hashFiles('**/composer.lock') }}
          fail-on-cache-miss: true

      - name: Run lint
        run: composer test:lint
```

`test-refactor.yml` is identical except it runs `composer test:refactor`.

`test-type-coverage.yml` (needs xdebug):
- `coverage: xdebug`, full `extensions` list, `timeout-minutes: 10`
- restore `vendor` with `fail-on-cache-miss: true`
- `run: composer test:type-coverage`

`test-unit.yml` (the heavy one — replaces all matrix "needs-db" steps):
- `timeout-minutes: 20`, `coverage: xdebug`, full `extensions` list
- `Setup Node` + restore `node_modules` (key `...-node-modules-${{ hashFiles('**/package-lock.json') }}`, `fail-on-cache-miss: true`)
- restore `public/build` (key `...-assets-${{ github.sha }}`, `fail-on-cache-miss: true`)
- cache `~/.cache/puppeteer` (key by puppeteer version / `package-lock.json`)
- `npx --yes puppeteer browsers install chrome`
- `cp .env.example .env` then `php artisan key:generate`
- `run: composer test:unit`
- `upload-artifact@v7` of `storage/logs/laravel.log`, `if: always()`, `retention-days: 1`
- **No** db creation / migrations: rely on `phpunit.xml` (`DB_DATABASE=:memory:`); do **not** export `DB_CONNECTION`/`DB_DATABASE` (letting `phpunit.xml` win).

### 3. REWRITE `.github/workflows/tests.yml` (orchestrator, name `Tests`)

```yaml
name: Tests

on:
  pull_request:
    branches: [ "main" ]
  workflow_call:
  workflow_dispatch:

concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

jobs:
  setup:
    uses: ./.github/workflows/setup.yml

  test-lint:
    needs: setup
    uses: ./.github/workflows/test-lint.yml

  test-refactor:
    needs: setup
    uses: ./.github/workflows/test-refactor.yml

  test-type-coverage:
    needs: setup
    uses: ./.github/workflows/test-type-coverage.yml

  test-unit:
    needs: setup
    uses: ./.github/workflows/test-unit.yml
```

- Keeps `pull_request` + `workflow_call` (so `deploy-to-k8s.yml` can still
  `needs: tests` and get all jobs as one status).
- `setup` is a hard gate: if install/build fails, nothing downstream runs.
- Optional `[skip tests]`: add
  `if: "!startsWith(github.event.head_commit.message, '[skip tests]')"` to the
  four test jobs (matches the reference; only meaningful on `push`).

### 4. REWRITE `.github/workflows/deploy-to-k8s.yml`

Split into `build` and `deploy`, both with timeouts, plus a non-cancelling
concurrency group (never cancel a prod rollout).

```yaml
name: Deploy to Kubernetes

on:
  push:
    branches: [ "main" ]
  workflow_dispatch:

concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: false

env:
  K8S_CONTAINER: sertxudev
  K8S_DEPLOYMENT: sertxudev
  K8S_NAMESPACE: sertxudev

jobs:
  tests:
    uses: ./.github/workflows/tests.yml

  build:
    needs: tests
    runs-on: ubuntu-latest
    timeout-minutes: 30
    environment: production
    steps:
      - Checkout, docker/login-action, setup-buildx-action,
        metadata-action, build-push-action  # as today

  deploy:
    needs: build
    runs-on: ubuntu-latest
    timeout-minutes: 20
    environment: production
    steps:
      - appleboy/ssh-action@v1.2.5   # same microk8s script as today
```

Keep as-is (they are correct for this project): Docker Hub login via
`DOCKERHUB_*`, image `${{ github.repository }}`, `microk8s kubectl` deploy script.

### 5. KEEP `.github/workflows/dependabot-auto-merge.yml`

No change required **provided** `tests.yml` keeps the workflow name `Tests`
(`on.workflow_run.workflows: [ "Tests" ]`). Re-verify `conclusion == 'success'`
still fires now that the workflow has several jobs — it does; `workflow_run`
reports the overall conclusion.

### 6. `dependabot.yml`

No change. The reference adds a `composer` private registry for Flux; this repo
has no private Composer registry, so keep the current file.

---

## Reference patterns intentionally NOT adopted

| Reference thing | Why not |
|---|---|
| `runs-on: self-hosted` | No runners in this repo (see above). |
| `postgres:18-alpine` + `gotenberg` services | Tests use SQLite; no PDF/Gotenberg dependency. |
| Flux secrets + `composer.fluxui.dev`, Dependabot composer registry | Not a dependency here. |
| `test-translations.yml` (`translations:check`) | `sertxudeveloper/laravel-translations-checker` not installed. |
| `test-types.yml` (`phpstan analyse` / Larastan) | Larastan not installed; no `test:types` script. |
| `tia-baseline.yml` (Pest TIA) | Optional later (Pest 5 supports `--tia`); not in scope now. |
| `modules/*` test suites, `landlord`/`tenant` DBs, tenants migrations | Single-app, no multi-tenancy. |
| `lint.yml` auto-commit workflow | Diverges from this repo's convention (tests-only CI). Keep out. |
| `DOCKER_REGISTRY` + `webhook`/Reverb/Pulse deployments | Uses Docker Hub + only app/worker/scheduler deployments. |

---

## Optional / follow-ups (separate from the CI restructure)

1. **Docker build efficiency.** The current `Dockerfile` runs
   `composer install` then `npm install && npm run build`; the reference uses
   BuildKit cache mounts and `npm ci`. Consider `--mount=type=cache` for
   `~/.composer/cache` and `~/.npm`, `npm ci` instead of `npm install`, and an
   `ARG APP_VERSION` (passed from `deploy` as a `build-args`) so the image can
   report its version. This is a Dockerfile change, out of the pure CI scope.
2. **Pest `--ci` flag.** The reference runs `pest ... --ci`. Consider adding it
   to `test:unit` / `test:type-coverage` for consistent, non-interactive output.
3. **TIA (optional).** Adopt `test:tia-baseline` + `--tia --filtered` like the
   reference once test suite runtime matters.
4. **`workflow_dispatch` on `tests.yml`** for manual runs (add, low cost).

---

## Verification checklist

- [ ] `actionlint` / GitHub Actions YAML validation passes on all workflows.
- [ ] Open a PR: `setup` runs once, `vendor`/`node_modules`/`public/build`
      restored in downstream jobs (`fail-on-cache-miss` never triggers).
- [ ] Each test job: correct `coverage` (none for lint/refactor, xdebug otherwise).
- [ ] `test-unit` installs Chrome and the Browsershot OG-image test passes with the
      in-memory SQLite config (no workflow `DB_*` overrides).
- [ ] Failed run uploads `test-logs` artifact.
- [ ] Push to `main`: `Tests` passes → `build` pushes image → `deploy` rolls out
      app/worker/scheduler on the cluster.
- [ ] `Dependabot Auto-Merge` still triggers (workflow name `Tests` unchanged).
- [ ] Concurrency: two quick pushes to a PR cancel the first run; two merges to
      `main` do **not** cancel an in-progress deploy.
