# eNyaya

eNyaya is a Laravel 12 based Electronic Justice & Case Management Portal for academic case-management evaluation. It supports role-based dashboards, legal case registration, hearing scheduling, document uploads, reporting, notifications, REST API endpoints, MySQL relational storage, and MongoDB-oriented audit/document metadata logging.

## Stack

- PHP 8.2+, Laravel 12, Composer
- MySQL for users, roles, cases, hearings, and notifications
- MongoDB for document metadata, activity logs, audit history, system logs, and hearing notes
- Blade, Bootstrap 5, JavaScript, Chart.js

## Demo Accounts

After seeding, every account uses password `password`.

- `admin@enyaya.local` - Super Admin
- `court@enyaya.local` - Court Administrator
- `judge@enyaya.local` - Judge
- `advocate@enyaya.local` - Advocate/Lawyer
- `client@enyaya.local` - Client/User

## Local XAMPP Setup

1. Start Apache and MySQL in XAMPP.
2. Create a MySQL database named `enyaya`.
3. Copy `.env.example` to `.env` if needed and confirm:

```env
DB_CONNECTION=mysql
DB_DATABASE=enyaya
DB_USERNAME=root
DB_PASSWORD=
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DATABASE=enyaya_documents
```

4. Install dependencies and initialize the app:

```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

5. Open `http://127.0.0.1:8000`.

## Features Implemented

- Authentication: registration, login, logout, password reset link flow, email verification-ready user model
- Authorization: role model, role middleware, role-aware dashboards
- Case management: create, edit, delete, search, filtering, assignment to client/advocate/judge, status and priority tracking
- Hearing management: schedule, reschedule/update, courtroom allocation, calendar-style listing, hearing notes
- Document management: validated PDF/image uploads, secure local storage, MongoDB metadata logging with Laravel-log fallback
- Notifications: relational case notifications and API listing
- Reports: pending/completed case reports, monthly hearing report, user activity report, CSV export
- UI: formal responsive Bootstrap dashboard, sidebar, topbar, cards, tables, pagination, toast notifications, dark mode toggle
- APIs: `/api/auth/login`, `/api/cases`, `/api/cases/{case}`, `/api/hearings`, `/api/notifications`

## Database Design

MySQL tables:

- `roles`
- `users`
- `legal_cases`
- `hearings`
- `case_notifications`
- Laravel support tables: cache, jobs, sessions, password reset tokens

MongoDB collections:

- `activity_logs`
- `document_metadata`
- `audit_history`
- `system_logs`
- `hearing_notes`

If the MongoDB PHP client is unavailable during local review, writes are safely recorded through Laravel logs so the app remains runnable.

## Verification

Validated locally with:

```bash
php artisan route:list
php artisan test
php artisan migrate:fresh --seed
```

For production, configure a real mail transport, HTTPS, queue worker, scheduled tasks for hearing reminders, and a MongoDB PHP driver/client package.
