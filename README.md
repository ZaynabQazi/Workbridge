# Workbridge
Full-stack online job portal built with Laravel 11 MVC — role-based auth for candidates, employers &amp; admins, AI resume analyzer, AJAX live search, and real-time notifications.
# WorkBridge — Online Job Portal

> A full-stack job portal built with **Laravel 11 MVC** — connecting candidates, employers, and administrators on a single modern platform.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## About

WorkBridge is a semester project for **CSC336 Web Technologies** at COMSATS University Islamabad, Vehari Campus. It demonstrates a production-style MVC web application featuring role-based authentication, AI-assisted resume analysis, real-time AJAX search, and a full hiring pipeline across three user roles.

---

## Features

### Candidate
- Register and build a LinkedIn-style profile (headline, skills, education, experience, photo, CV PDF)
- Browse and live-search approved job listings (AJAX, no page reload)
- Apply with a cover letter and resume upload
- Save jobs for later and withdraw applications
- AI Resume Analyzer — upload CV, get match % scores and skill gap analysis against live jobs
- Real-time notifications (bell icon, unread badge, auto-polling)

### Employer
- Set up a company page with logo, industry, location, and description
- Post job vacancies (title, category, type, location, salary, deadline, requirements)
- Jobs go into a **pending** state until admin approves them
- Edit, close, reopen, or delete postings
- Review all applicants per job and update status (pending → reviewed → shortlisted → rejected)

### Admin
- Approve or reject employer-submitted jobs
- Suspend, activate, or permanently delete user accounts
- Manage job categories
- Dashboard with live platform stats and a **Chart.js weekly applications chart**

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11, PHP 8.2+ |
| Database | SQLite (easily switchable to MySQL) |
| ORM | Laravel Eloquent |
| Frontend | Bootstrap 5.3, jQuery 3.7, Chart.js |
| Icons | Bootstrap Icons 1.11 |
| Auth | Laravel session-based auth + role middleware |
| File Storage | Laravel Storage (public disk) |
| Templating | Blade |

---

## Project Structure

```
WorkBridge/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── CandidateController.php
│   │   │   ├── EmployerController.php
│   │   │   ├── AdminController.php
│   │   │   ├── HomeController.php
│   │   │   ├── JobSearchController.php
│   │   │   ├── NotificationController.php
│   │   │   └── ResumeAnalyzerController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Job.php
│       ├── Application.php
│       ├── Company.php
│       ├── Category.php
│       ├── CandidateProfile.php
│       ├── SavedJob.php
│       └── AppNotification.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── WorkBridgeSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── home.blade.php
│   ├── auth/
│   ├── jobs/
│   ├── candidate/
│   ├── employer/
│   ├── admin/
│   └── partials/
├── routes/web.php
└── public/assets/
    ├── workbridge.css
    └── workbridge.js
```

---

## Installation & Setup

### Requirements
- PHP 8.2+
- Composer
- XAMPP (or any local PHP server)

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/your-username/workbridge.git
cd workbridge
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Set up environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Run migrations and seed demo data**
```bash
php artisan migrate:fresh --seed
```

**5. Link storage for file uploads**
```bash
php artisan storage:link
```

**6. Start development server**
```bash
php artisan serve
```

**7. Open in browser**
```
http://localhost:8000
```

---

## Demo Accounts

After running `php artisan migrate:fresh --seed`:

| Role | Email | Password |
|---|---|---|
| Admin | admin@workbridge.test | Password123 |
| Employer | employer@workbridge.test | Password123 |
| Candidate | candidate@workbridge.test | Password123 |

---

## Key Routes

| Method | URI | Description |
|---|---|---|
| GET | `/` | Home page with featured jobs |
| GET | `/jobs` | Browse all approved jobs |
| GET | `/jobs/search` | AJAX live search endpoint |
| GET | `/jobs/{id}` | Job detail and apply |
| GET | `/candidate/dashboard` | Candidate dashboard |
| GET | `/candidate/profile` | Edit candidate profile |
| GET | `/candidate/resume-analyzer` | AI resume analyzer |
| GET | `/employer/dashboard` | Employer dashboard |
| GET | `/employer/jobs/create` | Post a new job |
| GET | `/employer/jobs/{id}/applicants` | View applicants |
| GET | `/admin/dashboard` | Admin dashboard |
| GET | `/admin/users` | Manage users |
| GET | `/admin/categories` | Manage categories |

---

## Screenshots

> Add screenshots to a `/screenshots` folder and update paths below.

| Home | Job Listings | Admin Dashboard |
|---|---|---|
| ![Home](screenshots/home.png) | ![Jobs](screenshots/jobs.png) | ![Admin](screenshots/admin.png) |

---

## License

This project is open source under the [MIT License](LICENSE).

---

## Author

**Your Name** — CIIT/SP22-BSSE-025/VHR  
COMSATS University Islamabad, Vehari Campus  
Course: CSC336 Web Technologies — Spring 2026  
Instructor: Yasmeen Jana
