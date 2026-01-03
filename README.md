# Droplets Dojo - Martial Arts Academy Management System

A comprehensive multi-dojo management system built with Laravel 12.

## Features

### Core Modules

1. **User & Role Management**
   - Multi-role support (Super Admin, Owner, Finance, Coach, Student, Parent)
   - Role-based access control (RBAC)
   - Parent-student linking
   - Account status management

2. **Member Management**
   - Member CRUD operations
   - QR code generation for check-in
   - Status management (active, leave, inactive)
   - Parent linking
   - Attendance tracking

3. **Class & Schedule Management**
   - Class management with capacity limits
   - Schedule creation and management
   - Enrollment system with conflict detection
   - Waitlist functionality

4. **Instructor Management**
   - Instructor profiles
   - Schedule assignment
   - Commission tracking

5. **Rank & Progress Tracking**
   - Rank/level management
   - Progress logging
   - Eligibility checking for promotions
   - Rank promotion workflow

6. **Payment & Finance**
   - Membership management
   - Invoice generation
   - Payment processing
   - Financial reports
   - Auto-invoice generation

7. **Event Management**
   - Event CRUD
   - Online registration
   - Digital certificates
   - Capacity management

8. **Communication**
   - Announcements
   - System notifications
   - Class messaging

9. **Reporting & Analytics**
   - Retention reports
   - Revenue reports
   - Attendance reports
   - Top classes analytics

10. **System & Configuration**
    - System settings
    - Master data management
    - Audit log viewer
    - Data export

11. **Branding & Public Pages**
    - Public dojo profiles
    - Event listings
    - Gallery showcase
    - Achievement display

12. **Security & Compliance**
    - Audit logging
    - Encryption service
    - Password strength checking
    - Activity monitoring

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy `.env.example` to `.env` and configure your database settings

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Seed initial data:
   ```bash
   php artisan db:seed
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

## Database Configuration

The system uses MySQL. Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dojo
DB_USERNAME=root
DB_PASSWORD=
```

## User Roles

- **Super Admin**: Full access to all dojos (Head Office)
- **Owner**: Dojo/Branch Manager with full access to their dojo
- **Finance**: Finance Manager with payment and invoice access
- **Coach**: Instructor with access to their classes and students
- **Student**: Access to own profile, classes, and progress
- **Parent**: Access to linked children's information

## API Endpoints

The system provides RESTful APIs organized by role:

- `/admin/*` - Super Admin routes
- `/owner/*` - Owner routes
- `/finance/*` - Finance routes
- `/coach/*` - Coach routes
- `/student/*` - Student routes
- `/parent/*` - Parent routes
- `/` - Public routes

## License

Proprietary
