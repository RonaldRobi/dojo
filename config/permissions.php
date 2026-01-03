<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permission Matrix
    |--------------------------------------------------------------------------
    |
    | Defines CRUD permissions for each resource by role.
    | 
    | SYSTEM TYPE: Internal Multi-Branch Management System
    | - All dojos are part of one organization managed by Head Office
    | - Super Admin (SA) = Head Office with full access to all branches
    | - Owner (OWN) = Branch Manager/Dojo Owner
    | 
    | Roles: 
    |   SA (Super Admin/Head Office) - Full access to all dojos
    |   OWN (Owner) - Dojo/Branch Manager
    |   FIN (Finance) - Finance Manager
    |   COA (Coach) - Instructor/Coach
    |   STU (Student) - Student
    |   PAR (Parent) - Parent of student
    | 
    | Actions: create, read, update, delete
    |
    */

    'permissions' => [
        // Member/Student Management
        'student.profile' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read', 'update'],
            'PAR' => ['read'],
        ],
        'attendance' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['create', 'read', 'update', 'delete'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'student.status' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'student.medical' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],

        // Class & Schedule Management
        'class.data' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'schedule' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'schedule.capacity' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'coach.assignment' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['read'],
        ],

        // Coach/Instructor Management
        'coach.profile' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read', 'update'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'coach.hours' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
        ],
        'coach.review' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['read'],
        ],
        'coach.certification' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['read'],
        ],

        // Rank, Belt & Progress Tracking
        'curriculum' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'progress.record' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['create', 'read', 'update', 'delete'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'grading.result' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['create', 'read', 'update', 'delete'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'belt.history' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],

        // Payment & Finance
        'invoice' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['create', 'read', 'update', 'delete'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'payment' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['create', 'read', 'update', 'delete'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'discount' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read', 'update'],
            'FIN' => ['create', 'read', 'update', 'delete'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'finance.report' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['create', 'read', 'update', 'delete'],
        ],

        // Event & Competition
        'event.setup' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'event.registration' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['create', 'read', 'update', 'delete'],
            'PAR' => ['create', 'read', 'update', 'delete'],
        ],
        'event.bracket' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'event.certificate' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],

        // Communication & Community
        'announcement' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'group.chat' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'COA' => ['create', 'read', 'update', 'delete'],
            'STU' => ['create', 'read', 'update', 'delete'],
            'PAR' => ['create', 'read', 'update', 'delete'],
        ],
        'notification' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['create', 'read', 'update', 'delete'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],

        // Reporting & Analytics
        'student.kpi' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['read'],
            'COA' => ['read'],
            'STU' => ['read'],
            'PAR' => ['read'],
        ],
        'class.analytics' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['read'],
            'COA' => ['read'],
        ],
        'coach.analytics' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['read'],
            'COA' => ['read'],
        ],
        'revenue.analytics' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['create', 'read', 'update', 'delete'],
        ],

        // System & Configuration
        'role.management' => [
            'SA' => ['create', 'read', 'update', 'delete'],
        ],
        'access.control' => [
            'SA' => ['create', 'read', 'update', 'delete'],
        ],
        'audit.log' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
            'FIN' => ['read'],
        ],
        'system.settings' => [
            'SA' => ['create', 'read', 'update', 'delete'],
            'OWN' => ['read'],
        ],
    ],
];

