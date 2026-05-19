# eNyaya

eNyaya is a Laravel 12 based Electronic Justice and Case Management Portal designed around Indian district court workflows. It combines case registration, hearing scheduling, cause list preparation, evidence metadata, role-based dashboards, reports, notifications, and audit logging into one academic judicial administration system.

The project is inspired by practical concepts seen in Indian court operations and public justice platforms, including daily cause lists, vakalatnama handling, hearing adjournments, courtroom allocation, advocate and judge assignment, document records, and pending/disposed case reporting.

## Project Objectives

- Digitize core court administration workflows for cases, hearings, documents, reports, and notifications.
- Provide role-specific access for super admins, court administrators, judges, advocates, and clients.
- Model Indian judicial concepts such as cause lists, adjournments, vakalatnama, case categories, courtroom scheduling, and case timelines.
- Store structured case data in a relational database while recording document metadata and audit history through MongoDB-oriented logging.
- Present a professional government-style dashboard that is clean, responsive, and suitable for academic demonstration.
- Leave clear extension points for AI summarization, delay prediction, and similar-case recommendations.

## Technology Stack

- PHP 8.2+
- Laravel 12
- Composer
- Blade templates
- Bootstrap 5
- Bootstrap Icons
- Chart.js
- MySQL or SQLite for relational application data
- MongoDB-oriented metadata logging with Laravel log fallback
- Vite-ready frontend structure

## Core Modules

### Authentication and Authorization

The application includes registration, login, logout, password reset request flow, and an email-verification-ready user model. Access is controlled through a role model and middleware.

Supported roles:

- Super Admin
- Court Administrator
- Judge
- Advocate/Lawyer
- Client/User

### Dashboard

The dashboard gives a quick operational view of the court system.

Displayed metrics include:

- Total cases
- Today's hearings
- Pending hearings
- Disposed cases
- Urgent matters
- Recent cases
- Upcoming hearings
- Judge workload
- Case status distribution
- User notifications

The interface uses a restrained government dashboard style: dark navy navigation, white content surfaces, soft gray backgrounds, emerald success states, and maroon legal alerts.

### Case Management

Cases can be created, edited, searched, filtered, viewed, and deleted. Each case can be linked to a client, advocate, and judge.

Tracked case fields include:

- Case number
- Title
- Judicial category
- Petitioner and respondent details
- Filing date
- Next hearing date
- Case status
- Case priority
- Assigned client
- Assigned advocate
- Assigned judge
- Summary
- Vakalatnama status

Supported case categories:

- Urgent
- Bail
- Civil
- Criminal
- Family
- Consumer
- Cyber Crime

Supported case timeline stages:

- Filed
- Accepted
- Under Review
- Hearing Scheduled
- In Progress
- Judgment Reserved
- Disposed

### Hearing Management

Hearings can be scheduled and updated from the hearing calendar. The system captures courtroom, hearing sequence, purpose, notes, and status.

Supported hearing statuses:

- Scheduled
- Rescheduled
- Completed
- Adjourned
- Cancelled

The hearing scheduler includes courtroom conflict prevention by checking whether another hearing already exists in the same courtroom within the same time window.

### Adjournment Tracking

Adjournments are recorded directly on hearings.

Tracked adjournment details:

- Number of adjourned hearings per case
- Who requested the adjournment
- Reason for adjournment

The case details page highlights adjournment pressure so repeated delays are easy to notice during review.

### Daily Cause List

The Cause List module generates a daily hearing list similar to court cause lists used in Indian judicial workflows.

Cause list features:

- Filter by date
- Filter by courtroom
- Filter by judge
- Auto-prioritize urgent, bail, criminal, and high-priority matters
- Display hearing sequence
- Display case number, parties, category, courtroom, judge, time, and priority
- Print-friendly export flow for PDF generation through the browser print dialog
- Court seal style watermark

Cause list route:

```text
/cause-list
```

### Evidence and Document Management

Documents are uploaded against a case and stored locally, while metadata is recorded through the document service.

Supported document categories:

- Evidence
- Vakalatnama
- Affidavit
- Petition
- Hearing Notice
- Other

Supported file types include PDFs, images, audio, and video files for realistic digital evidence handling.

Metadata captured:

- Case ID
- Case number
- Label
- Category
- Tags
- Original filename
- Stored path
- MIME type
- Size
- Version timestamp
- Uploading user

When a document is uploaded as a vakalatnama, the linked case is updated to mark the vakalatnama as pending verification.

### Legal Templates

The system provides downloadable starter templates for common legal documents:

- Affidavit
- Petition
- Vakalatnama
- Hearing Notice

Templates are exposed through:

```text
/templates/{template}
```

### Reports

Reports are available for authorized users such as super admins, court administrators, and judges.

Current reports include:

- Pending cases
- Completed cases
- Monthly hearing report
- User activity report
- CSV export
- AI future enhancement roadmap

### Notifications

The system includes case notification records and an API endpoint for notification listing. It is structured to support future automated reminders such as:

- Seven days before hearing
- One day before hearing
- Hearing-day alert
- Email delivery
- SMS-ready architecture
- Dashboard alerts

### API Endpoints

The project includes REST-style API endpoints for integration and mobile/frontend expansion.

Available API areas:

- Authentication
- Cases
- Hearings
- Notifications

Examples:

```text
POST /api/auth/login
GET  /api/cases
GET  /api/cases/{case}
GET  /api/hearings
GET  /api/notifications
```

## Database Design

Primary relational tables:

- `roles`
- `users`
- `legal_cases`
- `hearings`
- `case_notifications`
- Laravel support tables for cache, jobs, sessions, and password reset tokens

MongoDB-oriented collections/log streams:

- `activity_logs`
- `document_metadata`
- `audit_history`
- `system_logs`
- `hearing_notes`

If MongoDB support is unavailable during local review, the logging service safely falls back to Laravel logs so the application remains usable.

## Demo Accounts

After seeding, each demo account uses the password:

```text
password
```

Accounts:

- `admin@enyaya.local` - Super Admin
- `court@enyaya.local` - Court Administrator
- `judge@enyaya.local` - Judge
- `advocate@enyaya.local` - Advocate/Lawyer
- `client@enyaya.local` - Client/User

## Local Setup

### Option 1: SQLite

SQLite is convenient for quick local review.

1. Install PHP dependencies:

```bash
composer install
```

2. Copy the environment file if needed:

```bash
cp .env.example .env
```

3. Configure `.env`:

```env
DB_CONNECTION=sqlite
```

4. Generate the app key and initialize the database:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
```

5. Start the app:

```bash
php artisan serve
```

6. Open:

```text
http://127.0.0.1:8000
```

### Option 2: XAMPP and MySQL

1. Start Apache and MySQL in XAMPP.
2. Create a MySQL database named `enyaya`.
3. Configure `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=enyaya
DB_USERNAME=root
DB_PASSWORD=
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DATABASE=enyaya_documents
```

4. Install and initialize:

```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

5. Open:

```text
http://127.0.0.1:8000
```

## Useful Commands

Run tests:

```bash
php artisan test
```

List routes:

```bash
php artisan route:list
```

Reset and seed the database:

```bash
php artisan migrate:fresh --seed
```

Start the local server:

```bash
php artisan serve
```

## Verification

The current implementation has been verified with:

```bash
php artisan migrate
php artisan test
```

Expected test result:

```text
3 tests passed
```

## Future Enhancements

The project is structured so the following AI and automation capabilities can be added later:

- Hearing delay prediction using adjournment count, judge workload, category, and case age.
- AI legal summarization for pleadings, evidence, hearing notes, and orders.
- Similar-case recommendation system for research and decision support.
- Automated reminder scheduler for hearing notifications.
- Full SMS gateway integration.
- Evidence preview and secure document download workflows.
- Dedicated advocate profile and vakalatnama verification dashboard.
- Courtroom availability calendar with drag-and-drop scheduling.

## Production Notes

Before production deployment:

- Configure HTTPS.
- Configure a real mail transport.
- Run a queue worker for notifications and background jobs.
- Install and configure MongoDB PHP support if MongoDB persistence is required.
- Review file upload storage and access controls.
- Add backup policies for relational data and uploaded evidence.
- Harden authentication, authorization, audit retention, and document access.
