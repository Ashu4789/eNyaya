# eNyaya

eNyaya is a Laravel 12 based Electronic Justice and Case Management Portal designed around Indian district court workflows. It combines case registration, hearing scheduling, cause list preparation, evidence metadata, role-based dashboards, reports, notifications, and audit logging into one academic judicial administration system.

The project is inspired by practical concepts seen in Indian court operations and public justice platforms, including daily cause lists, vakalatnama handling, hearing adjournments, courtroom allocation, advocate and judge assignment, document records, and pending/disposed case reporting.

---

## Project Objectives

- **Workflow Digitization**: Core court administration workflows for cases, hearings, documents, reports, and notifications.
- **Role-Based Portals**: Role-specific access control for Super Admins, Court Administrators, Judges, Advocates, and Clients.
- **Indian Judicial Context**: Models concepts such as cause lists, adjournments, vakalatnama, case categories, courtroom scheduling, and case timelines.
- **Relational & Log Persistence**: Store structured case data in SQLite/MySQL while recording document metadata and audit history through MongoDB-oriented logging (with a safe local log fallback).
- **Public-Portal Aesthetics**: A clean government dashboard style (dark navy navigation, glassmorphic panels, Outfit typography, transition hover effects, and modern state tags).
- **Specialized Summon Notices**: Integration of asynchronous, role-specific email summons for clients, agenda notifications for advocates, and calendar docket alerts for judges.

---

## Technology Stack & Libraries Used

### Backend Framework
- **PHP 8.2+**
- **Laravel 12 (latest)**: Service layer architecture, Eloquent ORM, database migrations, and queue-based Mailables.

### Database Systems
- **SQLite / MySQL**: Used for primary transactional application data.
- **Search Optimization Indexes**: Custom database indexes applied to the `petitioner_name` and `respondent_name` columns in the `legal_cases` table to optimize query execution times during text-based case searches.
- **MongoDB**: Used for structured logs (activities, audit history, document metadata) via `mongodb/mongodb` client library.
- **Laravel Log Fallback**: Safe fallback to Laravel log files if MongoDB is not present.

### Frontend Integration
- **Vite & @tailwindcss/vite**: High-performance asset bundler compiling modern CSS/JS inputs.
- **Bootstrap 5 & Bootstrap Icons**: Responsive design system and navigation graphics.
- **Google Fonts (Outfit)**: Premium modern typography imported to enhance visual quality.
- **Chart.js**: Client-side status and category workload visualizers.

---

## Core Modules & Implemented Features

### 1. Authentication and Authorization
Access is controlled through a database role model and route middleware.
- **Roles**: Super Admin, Court Administrator, Judge, Advocate/Lawyer, Client/User.
- **Features**: Registration, login, logout, password resets, and role-based route middleware protection.

### 2. Dashboard
Gives a quick operational overview tailored to the logged-in user:
- **Metrics**: Total cases, today's hearings count, pending docket size, and disposed count.
- **Visuals**: Dynamic Chart.js status distribution doughnut, recent case lists, upcoming hearings, urgent matters, and judge workloads.

### 3. Case Management
Allows full lifecycle tracking of cases:
- **Fields**: Title, case number, filing date, petitioner/respondent names, lawyer/judge assignments, and vakalatnama status.
- **Centralized Enums**: Standardized categories (`Urgent`, `Bail`, `Civil`, `Criminal`, `Family`, `Consumer`, `Cyber Crime`), priority levels (`low`, `normal`, `high`, `urgent`), and case statuses.
- **Timeline**: Visual step tracking (Filed, Accepted, Under Review, Hearing Scheduled, In Progress, Judgment Reserved, Disposed).

### 4. Hearing & Adjournment Management
- **Courtroom Scheduler**: Direct scheduling with courtroom conflict checks to prevent time slot double-booking (30-minute buffers).
- **Adjournment Tracker**: Highlights case delay pressure by tracking adjournment count, requesting party, and reasons.
- **Specialized Mail Delivery**: Fires asynchronous emails to the Judge, Advocate, and Client when a hearing is scheduled or rescheduled.

### 5. Daily Cause List
- **Indian Court style list**: Filters by date, courtroom, and judge.
- **Prioritization**: Automatically ranks and orders urgent, bail, and criminal matters at the top.
- **Print support**: Print-friendly CSS formats the cause list with a court watermark seal for PDF saving.

### 6. Evidence & Case Document Center
- **Dynamic Uploads**: Supports PDF, images, video, and audio file formats.
- **Document List View**: Displays uploaded files directly in the case details page, showing metadata (size, tags, category).
- **Secure Downloads**: Links files to a download controller with path-validation rules to block directory traversal attacks (`403 Forbidden`).
- **Storage Fallback**: Checks MongoDB for file metadata. If MongoDB is disabled, it scans the local case directory to mock document lists so the feature remains fully functional.

### 7. Reports & Legal Templates
- **Reports**: Access to pending/completed case metrics, monthly hearing volumes, and advocate/judge case activity logs. Includes CSV exports.
- **Starter Templates**: Downloadable boilerplate HTML files for `Affidavit`, `Petition`, `Vakalatnama`, and `Hearing Notice`.

---

## Environment Variables (.env) Configuration

To run the application, copy the example environment file:
```bash
cp .env.example .env
```

Open `.env` and configure the following parameters:

### 1. Database Connections
- **Option A: SQLite (Default & Recommended for local review)**
  ```env
  DB_CONNECTION=sqlite
  ```
  *(The sqlite database file is automatically touched at `database/database.sqlite` during setup).*

- **Option B: MySQL (XAMPP / local server)**
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=enyaya
  DB_USERNAME=root
  DB_PASSWORD=
  ```

### 2. Email Service Credentials
- **Option A: Laravel Log Fallback (Default for local review)**
  ```env
  MAIL_MAILER=log
  ```
  *(All emails will be written to `storage/logs/laravel.log` so you can inspect them without a mail server).*

- **Option B: Real SMTP Server (e.g. Mailtrap, Gmail, SendGrid)**
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=sandbox.smtp.mailtrap.io   # replace with your SMTP host
  MAIL_PORT=2525
  MAIL_USERNAME=your_smtp_username
  MAIL_PASSWORD=your_smtp_password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS="noreply@enyaya.gov.in"
  MAIL_FROM_NAME="eNyaya Legal Portal"
  ```

### 3. MongoDB Logging (Optional)
If you have MongoDB running locally, configure:
```env
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DATABASE=enyaya_documents
```
*(If left empty or uninstalled, logging falls back to standard logs and document listing falls back to disk scanning).*

---

## Local Setup & Execution Guide

Follow these steps to run the project from a fresh clone:

### Step 1: Install Dependencies
Install PHP libraries via Composer:
```bash
composer install
```
Install frontend node packages:
```bash
npm install
```

### Step 2: Set Up Environment & Keys
Copy the env template:
```bash
cp .env.example .env
```
Generate the cryptographic application key:
```bash
php artisan key:generate
```

### Step 3: Initialize Database & Seed Demo Data
Run the migrations and populate the database with Indian court demo records:
```bash
php artisan migrate:fresh --seed
```

### Step 4: Link Storage Folder
Create the public symlink for uploads (required for document uploads/downloads):
```bash
php artisan storage:link
```

### Step 5: Compile Frontend Assets
Build Vite CSS and JavaScript packages:
```bash
npm run build
```

### Step 6: Start Application Services
You can run the application services in one of two ways:

- **Option A: Run Concurrently (Recommended)**
  Run the developer build script which starts the web server, background queue worker (for sending emails), log monitor, and Vite asset compiler all in a single terminal:
  ```bash
  npm run dev
  ```

- **Option B: Run Individually**
  1. Start the HTTP server:
     ```bash
     php artisan serve
     ```
  2. In a separate terminal, start the queue worker to process the emails:
     ```bash
     php artisan queue:listen
     ```

Open your browser and navigate to:
```text
http://127.0.0.1:8000
```

---

## Seeded Demo Accounts

Each demo account uses the password:
```text
password
```

| Role | Email | Access Provided |
| --- | --- | --- |
| **Super Admin** | `admin@enyaya.local` | Full platform access, all case files, audit trail, user listings, and reports. |
| **Court Administrator** | `court@enyaya.local` | Handles filing, courtroom scheduling, cause lists, and registrar operations. |
| **Judge** | `judge@enyaya.local` | Judicial portal displaying assigned docket list, hearing history, and delay alerts. |
| **Advocate/Lawyer** | `advocate@enyaya.local` | Access to client cases, evidence upload center, vakalatnama requests, and schedules. |
| **Client/User** | `client@enyaya.local` | Access to own legal files, hearing dates, summonses, and notifications. |

Additional registered accounts can be found in [DatabaseSeeder.php](file:///c:/Users/ashut/OneDrive/Desktop/eNyaya/database/seeders/DatabaseSeeder.php).

---

## Verification & Troubleshooting

### Running Tests
Execute PHPUnit tests to check code compliance:
```bash
php artisan test
```

### Resetting the Database
To clear records and reseed from scratch, run:
```bash
php artisan migrate:fresh --seed
```
