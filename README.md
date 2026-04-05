# Volunteer App

Volunteer management app (Symfony 7.4) for projects, missions, enrollments, and admin communication.

## Requirements

- PHP 8.2+
- Composer
- Database supported by Doctrine (PostgreSQL/MySQL/SQLite)

## Local Setup

1. Install dependencies:
   `composer install`
2. Configure environment:
   `.env` / `.env.local`
3. Run migrations:
   `php bin/console doctrine:migrations:migrate`
4. Start dev server:
   `symfony server:start`
   or
   `php -S 127.0.0.1:8000 -t public`

## Docker Setup

- Start the stack for local development:
  `docker compose up --build`
- Start the production-style stack:
  `docker compose -f docker-compose.prod.yml up --build -d`
- App:
  `http://localhost:8080`
- Adminer:
  `http://localhost:9090`
- MailHog:
  `http://localhost:8025`

Compose now follows a base-plus-override pattern:

- [docker-compose.yml](/Users/moses/projects/volunteer-app/docker-compose.yml) contains the shared service definitions.
- [docker-compose.override.yml](/Users/moses/projects/volunteer-app/docker-compose.override.yml) switches `app` to the dev image and mounts the project for local development.
- [docker-compose.prod.yml](/Users/moses/projects/volunteer-app/docker-compose.prod.yml) is a standalone production-oriented deployment file with only `postgres`, `app`, and `web`.

The Docker setup now uses separate containers for:

- `app`: PHP-FPM / Symfony runtime
- `web`: the stock `nginx` image with the project Nginx config mounted directly from Compose

For [docker-compose.prod.yml](/Users/moses/projects/volunteer-app/docker-compose.prod.yml), set at least these environment variables before starting:

- `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD`
- `POSTGRES_DATA_DIR`
- `APP_SECRET`, `DATABASE_URL`, `MAILER_DSN`
- `BOOTSTRAP_ADMIN_EMAIL`, `BOOTSTRAP_ADMIN_PASSWORD`, `BOOTSTRAP_ORGANISATION_NAME`
- `SERVER_NAME`
- optionally `LETSENCRYPT_DIR`, `SSL_CERTIFICATE`, `SSL_CERTIFICATE_KEY`

## Ubuntu Production Example

Example deployment on Ubuntu 24.04 with Docker Engine, Docker Compose, and existing Let's Encrypt certificates, using a separate deployment directory outside the git checkout.

1. Install Docker:
   ```bash
   sudo apt update
   sudo apt install -y ca-certificates curl git make
   sudo install -m 0755 -d /etc/apt/keyrings
   curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
   sudo chmod a+r /etc/apt/keyrings/docker.gpg
   echo \
     "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
     $(. /etc/os-release && echo \"$VERSION_CODENAME\") stable" | \
     sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
   sudo apt update
   sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
   sudo usermod -aG docker $USER
   ```
   Log out and back in once so the `docker` group is active.

2. Create a deployment directory and check out the project as a subdirectory:
   ```bash
   mkdir -p ~/apps/volunteer-app-prod
   cd ~/apps/volunteer-app-prod
   git clone https://github.com/swmo/volunteer-app.git
   cd volunteer-app
   git checkout master
   cd ..
   ```

3. Create a production env file outside the repo:
   ```bash
   cat > ~/apps/volunteer-app-prod/.env.prod <<'EOF'
   POSTGRES_DB=volunteer
   POSTGRES_USER=volunteer
   POSTGRES_PASSWORD=change-this-password
   POSTGRES_DATA_DIR=/home/your-user/apps/volunteer-app-prod/postgres-data
   APP_SECRET=change-this-secret
   DATABASE_URL=pgsql://volunteer:change-this-password@postgres:5432/volunteer
   MAILER_DSN=smtp://mailhog:1025
   BOOTSTRAP_ADMIN_EMAIL=admin@example.org
   BOOTSTRAP_ADMIN_PASSWORD=change-this-admin-password
   BOOTSTRAP_ORGANISATION_NAME=Example Organisation
   SERVER_NAME=example.org
   LETSENCRYPT_DIR=/etc/letsencrypt
   SSL_CERTIFICATE=/etc/letsencrypt/live/example.org/fullchain.pem
   SSL_CERTIFICATE_KEY=/etc/letsencrypt/live/example.org/privkey.pem
   EOF
   ```

4. Create the local Postgres data directory next to `.env.prod`:
   ```bash
   mkdir -p ~/apps/volunteer-app-prod/postgres-data
   ```

5. Copy the deployment Makefile from the repo into the parent deployment directory:
   ```bash
   cp ~/apps/volunteer-app-prod/volunteer-app/Makefile.deploy.example ~/apps/volunteer-app-prod/Makefile
   ```
   The `Makefile` should live next to `.env.prod`, one directory above the git checkout.

6. Run the initial production setup from the deployment directory:
   ```bash
   cd ~/apps/volunteer-app-prod
   make prod-init
   ```

7. Update on later deploys:
   ```bash
   cd ~/apps/volunteer-app-prod
   make prod-update
   ```

8. Create a database backup:
   ```bash
   cd ~/apps/volunteer-app-prod
   make prod-backup
   ```
   This writes a dump like `backups/postgres-monday.sql` in the parent deployment directory.

   Add it to crontab, for example to run every day at 02:30:
   ```bash
   crontab -e
   ```
   Then add:
   ```cron
   30 2 * * * cd /home/your-user/apps/volunteer-app-prod && make prod-backup
   ```
   Use the parent deployment directory that contains `.env.prod` and `Makefile`, not the `volunteer-app` git checkout itself.

9. Restore a database dump:
   ```bash
   cd ~/apps/volunteer-app-prod
   make prod-restore DUMP=backups/postgres-monday.sql
   ```
   This drops and recreates the configured PostgreSQL database before importing the dump.

10. Stop the production stack:
   ```bash
   cd ~/apps/volunteer-app-prod
   make prod-down
   ```

Notes:

- This keeps `.env.prod` and deployment commands outside the git repository itself.
- [Makefile.deploy.example](/Users/moses/projects/volunteer-app/Makefile.deploy.example) is intended to be copied to the parent deployment directory as `Makefile`.
- `make prod-init` is the first-run setup for a fresh server. It creates the Postgres data directory, starts the stack, runs migrations, and creates the minimum admin data.
- `make prod-init` is the first-run setup for a fresh server. It creates the Postgres data directory, starts the stack, creates the current Doctrine schema, marks existing migrations as applied, and creates the minimum admin data.
- `make prod-init` only creates the schema on an empty database. If the database is partially initialized, it stops with an error instead of trying to create duplicate tables or sequences.
- `make prod-bootstrap` can be used later to re-apply the minimum admin/bootstrap data without rebuilding the stack.
- `make prod-backup` creates a PostgreSQL dump in `backups/` with the weekday in the filename.
- `make prod-restore DUMP=...` drops and recreates the configured PostgreSQL database, then imports the given SQL dump.
- `make prod-restore` uses `template1` as the PostgreSQL maintenance database and terminates active connections before dropping the target database.
- `make prod-down` stops the production stack.
- `make prod-update` also refreshes the parent-directory `Makefile` from `volunteer-app/Makefile.deploy.example` after `git pull`.
- `.env.prod` is treated as a Docker Compose env file, so it does not need to be shell-sourceable.
- After changing values in `.env.prod`, recreate the containers with `make prod-up` or `make prod-update` before running `make prod-bootstrap`, so the new env vars are present inside the `app` container.
- Postgres data is stored in the directory referenced by `POSTGRES_DATA_DIR`, for example `~/apps/volunteer-app-prod/postgres-data`, next to `.env.prod`.
- Initial setup uses `doctrine:schema:create` because the repository does not yet contain a complete migration history for bootstrapping a fresh production database from scratch.
- If an initial setup failed halfway through, remove the fresh data directory referenced by `POSTGRES_DATA_DIR` and run `make prod-init` again, or restore a known-good backup before retrying.
- `--project-directory volunteer-app` makes Compose resolve the project relative to the checked-out app directory even when you run the command from the parent folder.
- This example assumes your TLS certificates already exist under `/etc/letsencrypt`.
- `MAILER_DSN` should point to your real SMTP provider in production.
- If port `80` or `443` is already used by another reverse proxy, adjust `HTTP_PORT` and `HTTPS_PORT` in `.env.prod`.

## Recent Functional Changes

- Enrollments support soft delete via `status` (`deleted`).
- Soft-deleted enrollments are excluded from:
  - admin enrollment list
  - enrollment export (Excel)
  - volunteer counters
- Admin menu **Kommunikation** now opens a DB-backed editor for the registration email template.
- Registration emails are rendered from database template content (Twig/HTML), with fallback to `templates/emails/registration.html.twig` when first created.

## Communication Template (Admin)

- Path: `/admin/communication/registration-template`
- Stored in table: `communication_template`
- Key used for registration email: `registration_email`
- Template supports Twig expressions with these variables:
  - `enrollment`
  - `projectManager`

## Notes

This project is still a work in progress and not marked as production-ready.
