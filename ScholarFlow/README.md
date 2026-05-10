# ScholarFlow 🎓

A modern, clean **PHP + MySQL MVC** Scholarship Registration System — structured like ASP.NET MVC but powered by pure PHP.

---

## ✨ Features

| Feature | Details |
|---|---|
| 🔐 Authentication | Register, Login, Logout with bcrypt hashing & CSRF protection |
| 🎓 Scholarship Listings | Active scholarships with type badges (Exclusive / Open) |
| 📄 Smart Application System | Exclusive scholarship conflict detection |
| 📁 Document Uploads | Transcript, ID, Recommendation Letter (PDF/JPG/PNG) |
| 👀 Reviewer Dashboard | Approve / Reject with notes |
| ⚙️ Admin Panel | Full CRUD for Users, Scholarships, Applications |
| 📱 Responsive UI | Mobile-first sidebar + Bootstrap 5 |
| 🔒 Security | Prepared statements, CSRF tokens, role-based access, session management |

---

## 📁 Project Structure

```
ScholarFlow/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php        ← Login, Register, Logout
│   │   ├── StudentController.php     ← Dashboard, Profile, Scholarships
│   │   ├── ApplicationController.php ← Apply, Track Applications
│   │   ├── ReviewerController.php    ← Review & Decide
│   │   ├── AdminController.php       ← Full Admin CRUD
│   │   └── ErrorController.php       ← 404, 403
│   ├── Models/
│   │   ├── User.php
│   │   ├── Scholarship.php           ← includes allows_multiple logic
│   │   ├── Application.php
│   │   └── Document.php
│   └── Views/
│       ├── auth/          login.php, register.php
│       ├── student/       dashboard, scholarships, apply, applications, profile
│       ├── reviewer/      dashboard, applications, review
│       ├── admin/         dashboard, users, scholarships, applications + forms
│       ├── layouts/       header, footer, sidebar, flash
│       └── errors/        404, 403
│
├── config/
│   └── database.php               ← DB credentials + app constants
│
├── core/
│   ├── App.php                    ← Bootstrap, PDO singleton, session
│   ├── Router.php                 ← URL routing engine
│   ├── Controller.php             ← Base controller (view, redirect, CSRF, upload)
│   └── Model.php                  ← Base model (find, insert, update, delete)
│
├── public/
│   ├── index.php                  ← Single entry point
│   ├── setup.php                  ← One-time database seeder (delete after use!)
│   ├── .htaccess                  ← URL rewriting
│   ├── assets/
│   │   ├── css/app.css            ← Full design system CSS
│   │   └── js/app.js
│   └── uploads/                   ← User-uploaded files (auto-created)
│
├── routes/
│   └── web.php                    ← All routes defined here
│
└── scholarflow_db.sql             ← Full schema + seed data
```

---

## 🚀 Quick Setup (XAMPP)

### Step 1 — Clone / copy files

```
C:\xampp\htdocs\ScholarFlow\
```

### Step 2 — Configure database

Edit `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'scholarflow_db');
define('DB_USER', 'root');
define('DB_PASS', '');           // your MySQL password
define('APP_URL', 'http://localhost/ScholarFlow/public');
```

### Step 3 — Enable mod_rewrite (Apache)

In `httpd.conf`, ensure:
```
LoadModule rewrite_module modules/mod_rewrite.so
```

In your VirtualHost or `httpd-vhosts.conf`:
```apache
<Directory "C:/xampp/htdocs/ScholarFlow/public">
    AllowOverride All
    Require all granted
</Directory>
```

### Step 4 — Run the setup script

Start XAMPP (Apache + MySQL), then visit:

```
http://localhost/ScholarFlow/public/setup.php
```

This will:
- Create the `scholarflow_db` database and all tables
- Seed 3 users and 6 scholarships
- Create upload directories

### Step 5 — Delete setup.php

```bash
rm C:\xampp\htdocs\ScholarFlow\public\setup.php
```

### Step 6 — Open the app

```
http://localhost/ScholarFlow/public/
```

---

## 🔑 Default Login Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@scholarflow.com | `Admin@1234` |
| **Reviewer** | reviewer@scholarflow.com | `Admin@1234` |
| **Student** | student@scholarflow.com | `Student@1234` |

> ⚠️ Change all passwords immediately after first login in production!

---

## 🧠 Scholarship Conflict Logic

```
allows_multiple = 0  →  EXCLUSIVE
allows_multiple = 1  →  OPEN (stackable)
```

**Rule:**
- If a student has an **approved** application for an **exclusive** scholarship → all other exclusive scholarships become **locked** (unavailable).
- Open scholarships can always be applied to freely.
- A student cannot apply to the same scholarship twice (enforced by DB unique key).

---

## 🛣️ Routes Reference

```php
// Auth
GET  /login              → AuthController@loginForm
POST /login              → AuthController@authenticate
GET  /register           → AuthController@registerForm
POST /register           → AuthController@register
GET  /logout             → AuthController@logout

// Student
GET  /dashboard          → StudentController@dashboard
GET  /scholarships       → StudentController@scholarships
GET  /scholarships/:id   → StudentController@scholarshipDetail
GET  /profile            → StudentController@profileForm
POST /profile            → StudentController@updateProfile

// Applications
GET  /apply/:id          → ApplicationController@form
POST /apply/:id          → ApplicationController@submit
GET  /applications       → ApplicationController@myApplications
GET  /applications/:id   → ApplicationController@show

// Reviewer
GET  /reviewer                     → ReviewerController@dashboard
GET  /reviewer/applications        → ReviewerController@applications
GET  /reviewer/applications/:id    → ReviewerController@review
POST /reviewer/applications/:id    → ReviewerController@decide

// Admin
GET  /admin                            → AdminController@dashboard
GET  /admin/users                      → AdminController@users
GET  /admin/users/create               → AdminController@createUserForm
POST /admin/users/create               → AdminController@createUser
GET  /admin/users/:id/edit             → AdminController@editUserForm
POST /admin/users/:id/edit             → AdminController@updateUser
POST /admin/users/:id/delete           → AdminController@deleteUser
GET  /admin/scholarships               → AdminController@scholarships
GET  /admin/scholarships/create        → AdminController@createScholarshipForm
POST /admin/scholarships/create        → AdminController@createScholarship
GET  /admin/scholarships/:id/edit      → AdminController@editScholarshipForm
POST /admin/scholarships/:id/edit      → AdminController@updateScholarship
POST /admin/scholarships/:id/delete    → AdminController@deleteScholarship
GET  /admin/applications               → AdminController@applications
```

---

## 🔒 Security Features

| Feature | Implementation |
|---|---|
| Password hashing | `password_hash()` with `PASSWORD_BCRYPT` |
| SQL injection prevention | PDO prepared statements throughout |
| CSRF protection | Token per session, verified on all POST requests |
| Role-based access | `requireRole()` in base Controller |
| Session security | `httponly`, `strict_mode`, periodic regeneration |
| File upload validation | Extension whitelist + file size limit (5MB) |
| Directory listing | `Options -Indexes` in .htaccess |
| Sensitive file protection | `.env`, `.sql`, `.log` blocked via .htaccess |

---

## 🗂️ Old System → New System Mapping

| Old File | New Location |
|---|---|
| `login.php` | `AuthController@loginForm` |
| `process_login.php` | `AuthController@authenticate` |
| `register.php` | `AuthController@registerForm` |
| `process_register.php` | `AuthController@register` |
| `application.php` | `ApplicationController@form` |
| `process_application.php` | `ApplicationController@submit` |
| `admin_dashboard.php` | `AdminController@dashboard` |
| `reviewer_dashboard.php` | `ReviewerController@dashboard` |
| `profile.php` | `StudentController@profileForm` |
| `db.php` | `config/database.php` + `core/App.php` |

---

## 📦 Tech Stack

- **Backend:** PHP 8.1+ (pure, no frameworks)
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Frontend:** Bootstrap 5.3, Bootstrap Icons 1.11
- **Fonts:** Syne (display) + DM Sans (body) via Google Fonts
- **Architecture:** MVC (ASP.NET MVC-inspired)

---

## 🛠️ Extending the System

### Add a new route:
```php
// routes/web.php
$router->get('/my-page', 'MyController@index');
```

### Add a new controller:
```php
// app/Controllers/MyController.php
require_once ROOT . '/core/Controller.php';

class MyController extends Controller {
    public function index(): void {
        $this->requireAuth();
        $this->view('my.index', ['title' => 'My Page']);
    }
}
```

### Add a new model:
```php
// app/Models/MyModel.php
require_once ROOT . '/core/Model.php';

class MyModel extends Model {
    protected static string $table = 'my_table';
}
```

---

## 📝 License

MIT — Free to use for educational purposes.

---

*Built with ❤️ for ScholarFlow — Cebu, Philippines*
