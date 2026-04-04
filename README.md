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
- App:
  `http://localhost:8080`
- Adminer:
  `http://localhost:9090`
- MailHog:
  `http://localhost:8025`

Compose now follows a base-plus-override pattern:

- [docker-compose.yml](/Users/moses/projects/volunteer-app/docker-compose.yml) contains the shared service definitions.
- [docker-compose.override.yml](/Users/moses/projects/volunteer-app/docker-compose.override.yml) switches `app` and `web` to the dev images and mounts the project for local development.

The Docker setup now uses separate containers for:

- `app`: PHP-FPM / Symfony runtime
- `web`: Nginx reverse proxy serving `public/` and forwarding PHP requests to `app:9000`

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
