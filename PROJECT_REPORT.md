# WorkBridge - Online Job Portal

WorkBridge is a Laravel 12 full-stack web application with candidate, employer, and administrator roles. It includes authentication, role middleware, LinkedIn-inspired dashboards, CRUD workflows, AJAX job search, in-app notifications, secure image/CV upload validation, seed data, and a deterministic AI resume analyzer.

## Demo Accounts

- Admin: `admin@workbridge.test` / `Password123`
- Employer: `employer@workbridge.test` / `Password123`
- Candidate: `candidate@workbridge.test` / `Password123`

## Important Features

- Candidate: LinkedIn-style profile, profile image, skills, resume/CV PDF upload, job search, apply, save jobs, withdraw applications, status tracking, notifications, AI resume analyzer.
- Employer: company page, company logo/image upload, job posting, edit/delete/close/reopen jobs, applicants, status updates, notifications.
- Admin: user management, categories, job approval/rejection, analytics with Chart.js.
- AJAX: live job search, notifications, JSON resume analyzer responses, interactive file upload feedback.
- Security: bcrypt hashing, CSRF, validation requests, Eloquent queries, role middleware, PDF/image size validation.

## Run With SQLite

1. Open PowerShell in this folder.
2. Run `composer install`.
3. Run `copy .env.example .env` if `.env` does not exist.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate:fresh --seed`.
6. Run `php artisan storage:link`.
7. Run `php artisan serve`.
8. Open `http://127.0.0.1:8000`.

## Run With MySQL

Create a MySQL database named `workbridge`, then update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workbridge
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan migrate:fresh --seed
php artisan serve
```

## Verify

Run `php artisan test` to verify home/search, role protection, and AI resume matching.
