# School Management System

A medium-level School Management System built with Core PHP, MySQL, and Bootstrap 5.

## Features
- **Admin Module**: Manage students, teachers, subjects, marks, and attendance.
- **Teacher Module**: Manage attendance, marks, students, and subjects.
- **Student Module**: View attendance, marks, and assigned subjects.
- **Security**: Role-based access control, secure login, and password hashing.
- **UI/UX**: Premium dark-themed glassmorphism design using Bootstrap 5 and custom CSS.

## Setup Instructions

1. **Database Setup**:
   - The database configuration is in `config/db.php`.
   - Run `http://localhost/school_management/setup_database.php` once to create the database and tables. (This has already been executed).

2. **Default Credentials**:
   - **Admin**: `admin@school.com` / `admin123`

## Directory Structure
- `admin/`: Admin files (dashboard, management pages).
- `teacher/`: Teacher files.
- `student/`: Student files.
- `includes/`: Header, footer, navbar.
- `config/`: Database connection.
- `assets/`: CSS and other static assets.

## Usage
- Open `http://localhost/school_management/index.php` in your browser.
- Log in with the default admin credentials.
- Create Teacher accounts from the Admin dashboard.
- Create Student accounts from the Admin or Teacher dashboard.
