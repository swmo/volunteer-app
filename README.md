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
- `APP_SECRET`, `DATABASE_URL`, `MAILER_DSN`
- `SERVER_NAME`
- optionally `LETSENCRYPT_DIR`, `SSL_CERTIFICATE`, `SSL_CERTIFICATE_KEY`

## Ubuntu Production Example

Example deployment on Ubuntu 24.04 with Docker Engine, Docker Compose, and existing Let's Encrypt certificates.

1. Install Docker:
   ```bash
   sudo apt update
   sudo apt install -y ca-certificates curl git
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

2. Check out the project:
   ```bash
   git clone https://github.com/swmo/volunteer-app.git
   cd volunteer-app
   git checkout master
   ```

3. Create a production env file:
   ```bash
   cat > .env.prod <<'EOF'
   POSTGRES_DB=volunteer
   POSTGRES_USER=volunteer
   POSTGRES_PASSWORD=change-this-password
   APP_SECRET=change-this-secret
   DATABASE_URL=pgsql://volunteer:change-this-password@postgres:5432/volunteer
   MAILER_DSN=smtp://mailhog:1025
   SERVER_NAME=example.org
   LETSENCRYPT_DIR=/etc/letsencrypt
   SSL_CERTIFICATE=/etc/letsencrypt/live/example.org/fullchain.pem
   SSL_CERTIFICATE_KEY=/etc/letsencrypt/live/example.org/privkey.pem
   EOF
   ```

4. Start the production stack:
   ```bash
   docker compose --env-file .env.prod -f docker-compose.prod.yml up --build -d
   ```

5. Run database migrations:
   ```bash
   docker compose --env-file .env.prod -f docker-compose.prod.yml exec app php bin/console doctrine:migrations:migrate --no-interaction
   ```

6. Update on later deploys:
   ```bash
   git pull
   docker compose --env-file .env.prod -f docker-compose.prod.yml up --build -d
   docker compose --env-file .env.prod -f docker-compose.prod.yml exec app php bin/console doctrine:migrations:migrate --no-interaction
   ```

Notes:

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
