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
