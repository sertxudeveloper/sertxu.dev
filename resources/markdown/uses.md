## Core stack

I build, test, and deploy production-ready Laravel applications with a strong focus on reliability, automation, and maintainability.

- **Backend:** Laravel, Queues, Task Scheduling, and real-time WebSockets using Socketi
- **Frontend:** Livewire, Filament (admin panels), Tailwind CSS
- **Database & Storage:** PostgreSQL (CloudNativePG + Barman for backups), S3-compatible storage (Cloudflare R2, Garage, or AWS S3)
- **Infrastructure:** Docker, Kubernetes, Traefik, Nginx
- **Security & TLS:** Certbot (Let's Encrypt ACME) with Cloudflare DNS-01 challenges
- **Observability:** Sentry, Grafana, Prometheus (with Blackbox and Node Exporter for uptime and host-level metrics)
- **Testing:** Pest & PHPUnit
- **CI/CD:** GitHub Actions (both SaaS and self-hosted runners)

## Development workflow

For local development, I use Laravel Herd. For day-to-day coding, I rely on PhpStorm (with the Laravel Idea plugin) and Visual Studio Code. I use Git from the command line, host my repositories on GitHub, and use Figma for wireframes, mockups, logos, and design work.

## Deployment pipeline

My workflow is fully automated. When all tests pass, the application is Dockerized, pushed to a container registry (Docker Hub or self-hosted), and deployed to my Kubernetes clusters using a zero-downtime rollover strategy.

## Less frequent work

When a project calls for it, I also work with Electron, Ionic, C#, .NET, MariaDB, Microsoft SQL Server, Apache, Plesk, cPanel, and WordPress.
