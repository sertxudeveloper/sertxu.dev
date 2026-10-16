## Core stack

I build Laravel applications that are easy to maintain and stay online without worrying about Friday releases.

### Application

- **Backend:** Laravel, with queues, scheduled tasks, and real-time updates via WebSockets (Socketi)

- **Frontend:** Livewire, Filament admin panels, Tailwind CSS, Lucide Icons, and Alpine.js

### Data you can trust

- **Database:** PostgreSQL on CloudNativePG, with Barman for backup and point-in-time recovery

- **Storage:** S3-compatible via Cloudflare R2 or self-hosted Garage

### Infrastructure that doesn't wake you at 3 AM

- **Platform:** Docker, Kubernetes, Traefik, and Nginx, with zero-downtime rollouts

- **Hosting:** Bare-metal Proxmox with Ubuntu Server.

- **Security:** Automated TLS via Let's Encrypt, Certbot, and Cloudflare DNS-01 challenge

- **Monitoring:** Sentry for errors and traces, Prometheus and Grafana for metrics and dashboards, connected with Blackbox, Node Exporter, and kube-state-metrics for uptime, host health, and K8s health

### Quality and delivery

- **Testing:** Pest and PHPUnit, integrated into CI

- **CI/CD:** GitHub Actions (SaaS and self-hosted runners). When all tests pass, the app is containerized, pushed to a registry (Docker Hub or self-hosted), and rolled out to Kubernetes with zero downtime.

## My daily tools

For local development, I use Laravel Herd, PhpStorm (with Laravel Idea), VS Code, Git and GitHub, and Figma for wireframes, mockups, and logos.

## Less frequent work

When a project calls for it, I work with Electron, Ionic, C#, .NET, MariaDB, Microsoft SQL Server, Apache, Plesk, cPanel, and WordPress.

I also train custom scikit-learn models and serve them for inference with FastAPI and Uvicorn.
