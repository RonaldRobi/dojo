<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DojoController as AdminDojoController;
use App\Http\Controllers\Admin\ComingSoonController;
use App\Http\Controllers\Admin\CurriculumController;
use App\Http\Controllers\Admin\InstructorManagementController;
use App\Http\Controllers\Admin\MemberManagementController;
use App\Http\Controllers\Admin\FinanceManagementController;
use App\Http\Controllers\Admin\EventManagementController;
use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\Owner\MemberController;
use App\Http\Controllers\Owner\AttendanceController;
use App\Http\Controllers\Owner\ClassController;
use App\Http\Controllers\Owner\ScheduleController;
use App\Http\Controllers\Owner\EnrollmentController;
use App\Http\Controllers\Owner\InstructorController;
use App\Http\Controllers\Owner\RankController;
use App\Http\Controllers\Owner\ProgressController;
use App\Http\Controllers\Owner\EventController;
use App\Http\Controllers\Owner\EventRegistrationController;
use App\Http\Controllers\Owner\AnnouncementController;
use App\Http\Controllers\Owner\NotificationController;
use App\Http\Controllers\Owner\MessageController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\GalleryController;
use App\Http\Controllers\Owner\AchievementController;
use App\Http\Controllers\Owner\InvoiceController as OwnerInvoiceController;
use App\Http\Controllers\Owner\PaymentController as OwnerPaymentController;
use App\Http\Controllers\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Finance\InvoiceController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\MembershipController;
use App\Http\Controllers\Finance\DashboardController as FinanceDashboardController;
use App\Http\Controllers\Coach\DashboardController as CoachDashboardController;
use App\Http\Controllers\Coach\ClassController as CoachClassController;
use App\Http\Controllers\Coach\ProgressController as CoachProgressController;
use App\Http\Controllers\Coach\AttendanceController as CoachAttendanceController;
use App\Http\Controllers\Coach\StudentController as CoachStudentController;
use App\Http\Controllers\Coach\EventController as CoachEventController;
use App\Http\Controllers\Coach\BroadcastingController as CoachBroadcastingController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ClassController as StudentClassController;
use App\Http\Controllers\Student\ProgressController as StudentProgressController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;
use App\Http\Controllers\Student\AnnouncementController as StudentAnnouncementController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\Parent\ChildController as ParentChildController;
use App\Http\Controllers\Parent\ScheduleController as ParentScheduleController;
use App\Http\Controllers\Parent\EventController as ParentEventController;
use App\Http\Controllers\Parent\PaymentController as ParentPaymentController;
use App\Http\Controllers\Parent\RegisterController as ParentRegisterController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Public\PublicEventController;
use App\Http\Controllers\Auth\LoginController;

// Public routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/dojo/{dojo}', [PublicController::class, 'showDojo'])->name('public.dojo');
Route::get('/events', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('public.events.show');

// PUBLIC Payment Gateway Routes (Bayar.cash callbacks - NO MIDDLEWARE!)
// These MUST be completely public for payment gateway to POST results
Route::match(['get', 'post'], 'parent/payment/return/{invoice}', [ParentPaymentController::class, 'paymentReturn'])
    ->name('parent.payment.return');
Route::post('parent/payment/callback', [ParentPaymentController::class, 'paymentCallback'])
    ->name('parent.payment.callback');
Route::post('parent/payment/webhook', [ParentPaymentController::class, 'paymentWebhook'])
    ->name('parent.payment.webhook');

// Authentication routes (NO MIDDLEWARE - handled in controller)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Parent Registration (Public)
Route::get('/register/parent', [\App\Http\Controllers\Auth\ParentRegisterController::class, 'showEmailForm'])->name('parent.register.email');
Route::post('/register/parent', [\App\Http\Controllers\Auth\ParentRegisterController::class, 'sendRegistrationLink'])->name('parent.register.send');
Route::get('/register/parent/{token}', [\App\Http\Controllers\Auth\ParentRegisterController::class, 'showRegistrationForm'])->name('parent.register.complete');
Route::post('/register/parent/{token}', [\App\Http\Controllers\Auth\ParentRegisterController::class, 'completeRegistration'])->name('parent.register.submit');

// API: Check username/email availability
Route::get('/api/check-username-availability', [App\Http\Controllers\Parent\RegisterController::class, 'checkUsernameAvailability'])->name('api.check.username');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Authenticated routes - role-based grouping
Route::middleware(['auth', 'ensure.account.active'])->group(function () {
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');
    Route::get('/menu-search', [\App\Http\Controllers\MenuSearchController::class, 'search'])->name('menu-search');
    
    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    
    // Dashboard routes
    Route::get('/dashboard', function() {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        } elseif ($user->hasRole('finance')) {
            return redirect()->route('finance.dashboard');
        } elseif ($user->hasRole('coach')) {
            return redirect()->route('coach.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        } elseif ($user->hasRole('parent')) {
            return redirect()->route('parent.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Super Admin routes
    Route::prefix('admin')->name('admin.')->middleware(['role:super_admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/sync', [AdminDashboardController::class, 'sync'])->name('dashboard.sync');
        
        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/api/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'apiList'])->name('notifications.api');
        Route::get('/api/notifications/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        
        Route::resource('users', UserController::class);
        Route::post('users/{user}/assign-role', [RoleManagementController::class, 'assignRole'])->name('users.assign-role');
        Route::post('users/{user}/remove-role', [RoleManagementController::class, 'removeRole'])->name('users.remove-role');
        Route::get('users/{user}/roles', [RoleManagementController::class, 'getUserRoles'])->name('users.roles');
        
        Route::get('dojos/{dojo}/assign-owner', [AdminDojoController::class, 'assignOwnerForm'])->name('dojos.assign-owner');
        Route::post('dojos/{dojo}/assign-owner', [AdminDojoController::class, 'assignOwner'])->name('dojos.assign-owner.store');
        Route::resource('dojos', AdminDojoController::class);
        
        Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
        Route::get('system/settings', [SystemController::class, 'getSettings'])->name('system.settings');
        Route::put('system/settings/{key}', [SystemController::class, 'updateSetting'])->name('system.settings.update');
        Route::get('system/dojos', [SystemController::class, 'getAllDojos'])->name('system.dojos');
        
        Route::prefix('reports')->name('reports.')->group(function() {
            Route::get('revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('events', [ReportController::class, 'events'])->name('events');
        });

        // Curriculum & Rank System
        Route::prefix('curriculum')->name('curriculum.')->group(function() {
            // Styles
            Route::get('styles', [\App\Http\Controllers\Admin\CurriculumController::class, 'styles'])->name('styles');
            Route::post('styles', [CurriculumController::class, 'storeStyle'])->name('styles.store');
            Route::put('styles/{style}', [CurriculumController::class, 'updateStyle'])->name('styles.update');
            Route::delete('styles/{style}', [CurriculumController::class, 'destroyStyle'])->name('styles.destroy');
            
            // Levels
            Route::get('levels', [CurriculumController::class, 'levels'])->name('levels');
            Route::post('levels', [CurriculumController::class, 'storeLevel'])->name('levels.store');
            Route::put('levels/{level}', [CurriculumController::class, 'updateLevel'])->name('levels.update');
            Route::delete('levels/{level}', [CurriculumController::class, 'destroyLevel'])->name('levels.destroy');
            
            // Belts
            Route::get('belts', [CurriculumController::class, 'belts'])->name('belts');
            Route::post('belts', [CurriculumController::class, 'storeBelt'])->name('belts.store');
            Route::put('belts/{belt}', [CurriculumController::class, 'updateBelt'])->name('belts.update');
            Route::delete('belts/{belt}', [CurriculumController::class, 'destroyBelt'])->name('belts.destroy');
            
            // Per Level
            Route::get('per-level', [CurriculumController::class, 'perLevel'])->name('per-level');
            Route::post('curriculums', [CurriculumController::class, 'storeCurriculum'])->name('curriculums.store');
            Route::put('curriculums/{curriculum}', [CurriculumController::class, 'updateCurriculum'])->name('curriculums.update');
            Route::delete('curriculums/{curriculum}', [CurriculumController::class, 'destroyCurriculum'])->name('curriculums.destroy');
            
            // Promotion Requirements
            Route::get('promotion-requirements', [CurriculumController::class, 'promotionRequirements'])->name('promotion-requirements');
            Route::post('requirements', [CurriculumController::class, 'storeRequirement'])->name('requirements.store');
            Route::put('requirements/{requirement}', [CurriculumController::class, 'updateRequirement'])->name('requirements.update');
            Route::delete('requirements/{requirement}', [CurriculumController::class, 'destroyRequirement'])->name('requirements.destroy');
            
            // National Grading
            Route::get('national-grading', [CurriculumController::class, 'nationalGrading'])->name('national-grading');
        });

        // Class & Schedule
        Route::prefix('classes')->name('classes.')->group(function() {
            // Schedule Management (MUST be before {id} routes to avoid conflicts!)
            Route::get('monitoring', [\App\Http\Controllers\Admin\ClassManagementController::class, 'monitoring'])->name('monitoring');
            Route::get('calendar', [\App\Http\Controllers\Admin\ClassManagementController::class, 'calendar'])->name('calendar');
            Route::post('schedule/store', [\App\Http\Controllers\Admin\ClassManagementController::class, 'storeSchedule'])->name('schedule.store');
            Route::get('schedule/{id}/edit', [\App\Http\Controllers\Admin\ClassManagementController::class, 'editSchedule'])->name('schedule.edit');
            Route::put('schedule/{id}', [\App\Http\Controllers\Admin\ClassManagementController::class, 'updateSchedule'])->name('schedule.update');
            Route::delete('schedule/{id}', [\App\Http\Controllers\Admin\ClassManagementController::class, 'destroySchedule'])->name('schedule.destroy');
            
            // Other specific routes
            Route::get('templates', [\App\Http\Controllers\Admin\ClassManagementController::class, 'templates'])->name('templates');
            Route::get('capacity-standards', [\App\Http\Controllers\Admin\ClassManagementController::class, 'capacityStandards'])->name('capacity-standards');
            Route::get('conflicts', [\App\Http\Controllers\Admin\ClassManagementController::class, 'conflicts'])->name('conflicts');
            
            // Class Management (Keep these at the end because of {id} parameter)
            Route::get('create', [\App\Http\Controllers\Admin\ClassManagementController::class, 'create'])->name('create');
            Route::post('store', [\App\Http\Controllers\Admin\ClassManagementController::class, 'store'])->name('store');
            Route::get('{id}/edit', [\App\Http\Controllers\Admin\ClassManagementController::class, 'edit'])->name('edit');
            Route::put('{id}', [\App\Http\Controllers\Admin\ClassManagementController::class, 'update'])->name('update');
            Route::delete('{id}', [\App\Http\Controllers\Admin\ClassManagementController::class, 'destroy'])->name('destroy');
            Route::get('{id}', [\App\Http\Controllers\Admin\ClassManagementController::class, 'show'])->name('show'); // MUST be last!
        });

        // Instructor Management
        Route::prefix('instructors')->name('instructors.')->group(function() {
            Route::get('/', [InstructorManagementController::class, 'index'])->name('index');
            Route::get('history', [InstructorManagementController::class, 'history'])->name('history');
            Route::get('certifications', [InstructorManagementController::class, 'certifications'])->name('certifications');
            Route::post('certifications', [InstructorManagementController::class, 'storeCertification'])->name('certifications.store');
            Route::get('certification-expiry', [InstructorManagementController::class, 'certificationExpiry'])->name('certification-expiry');
            Route::get('performance', [InstructorManagementController::class, 'performance'])->name('performance');
        });

        // Member Management (Global)
        Route::prefix('members')->name('members.')->group(function() {
            Route::get('/', [MemberManagementController::class, 'index'])->name('index');
            Route::get('attendance-global', [MemberManagementController::class, 'attendanceGlobal'])->name('attendance-global');
            Route::post('attendance-global/store', [MemberManagementController::class, 'storeAttendance'])->name('attendance.store');
            Route::post('attendance-global/bulk-store', [MemberManagementController::class, 'bulkStoreAttendance'])->name('attendance.bulk-store');
            Route::get('status', [MemberManagementController::class, 'status'])->name('status');
            Route::get('medical-notes', [MemberManagementController::class, 'medicalNotes'])->name('medical-notes');
        });

        // Payment & Finance (Global)
        Route::prefix('finance')->name('finance.')->group(function() {
            Route::get('payments', [FinanceManagementController::class, 'payments'])->name('payments');
            Route::get('revenue-all', [FinanceManagementController::class, 'revenueAll'])->name('revenue-all');
            Route::get('arrears', [FinanceManagementController::class, 'arrears'])->name('arrears');
            Route::get('cashflow', [FinanceManagementController::class, 'cashflow'])->name('cashflow');
        });

        // Event & Competition
        // Events Management (CRUD untuk semua dojo)
        Route::resource('events', \App\Http\Controllers\Admin\EventManagementController::class)->names([
            'index' => 'events.index',
            'create' => 'events.create',
            'store' => 'events.store',
            'show' => 'events.show',
            'edit' => 'events.edit',
            'update' => 'events.update',
            'destroy' => 'events.destroy',
        ]);
        
        Route::prefix('events')->name('events.')->group(function() {
            Route::get('national', [EventManagementController::class, 'national'])->name('national');
            Route::get('tournaments', [EventManagementController::class, 'tournaments'])->name('tournaments');
            Route::get('grading', [EventManagementController::class, 'grading'])->name('grading');
            Route::get('certificates', [EventManagementController::class, 'certificates'])->name('certificates');
            Route::get('history', [EventManagementController::class, 'history'])->name('history');
        });

        // Communication
        Route::prefix('communication')->name('communication.')->group(function() {
            Route::get('announcements', [CommunicationController::class, 'announcements'])->name('announcements');
            Route::post('announcements', [CommunicationController::class, 'storeAnnouncement'])->name('announcements.store');
            Route::get('broadcast', [CommunicationController::class, 'broadcast'])->name('broadcast');
            Route::post('broadcast', [CommunicationController::class, 'broadcast'])->name('broadcast.send');
            Route::get('message-templates', [CommunicationController::class, 'messageTemplates'])->name('message-templates');
            Route::post('message-templates', [CommunicationController::class, 'storeTemplate'])->name('message-templates.store');
            Route::get('notification-logs', [CommunicationController::class, 'notificationLogs'])->name('notification-logs');
        });

        // Pricing Settings
        Route::get('pricing', [\App\Http\Controllers\Admin\PricingController::class, 'index'])->name('pricing.index');
        Route::post('pricing', [\App\Http\Controllers\Admin\PricingController::class, 'update'])->name('pricing.update');

        // System Settings
        Route::get('settings/master-data', [\App\Http\Controllers\Admin\SystemController::class, 'masterData'])->name('settings.master-data');
        Route::get('settings/whatsapp', [\App\Http\Controllers\Admin\SystemController::class, 'whatsappIntegration'])->name('settings.whatsapp');
        Route::post('settings/whatsapp', [\App\Http\Controllers\Admin\SystemController::class, 'whatsappIntegration'])->name('settings.whatsapp.store');
        Route::get('settings/email', [\App\Http\Controllers\Admin\SystemController::class, 'emailIntegration'])->name('settings.email');
        Route::post('settings/email', [\App\Http\Controllers\Admin\SystemController::class, 'emailIntegration'])->name('settings.email.store');
        Route::get('organizations/structure', [ComingSoonController::class, 'index'])->defaults('page', 'Struktur Organisasi')->name('organizations.structure');
    });

    // Owner routes
    Route::prefix('owner')->name('owner.')->middleware(['ensure.dojo.access', 'role:owner'])->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        
        // Members - attendance routes (must be BEFORE resource route)
        Route::get('members/attendance', [MemberController::class, 'attendance'])->name('members.attendance');
        Route::post('members/attendance/bulk-store', [MemberController::class, 'bulkStoreAttendance'])->name('members.attendance.bulk-store');
        
        Route::resource('members', MemberController::class);
        Route::post('members/{member}/regenerate-qr', [MemberController::class, 'regenerateQR'])->name('members.regenerate-qr');
        
        Route::resource('attendances', AttendanceController::class);
        
        Route::resource('classes', ClassController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::post('enrollments/enroll', [EnrollmentController::class, 'enroll'])->name('enrollments.enroll');
        Route::put('enrollments/{enrollment}/unenroll', [EnrollmentController::class, 'unenroll'])->name('enrollments.unenroll');
        Route::get('enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        
        Route::resource('instructors', InstructorController::class);
        
        Route::resource('ranks', RankController::class);
        Route::resource('progress', ProgressController::class);
        Route::get('progress/{member}/eligibility/{rankId}', [ProgressController::class, 'checkEligibility'])->name('progress.eligibility');
        Route::post('progress/{member}/promote', [ProgressController::class, 'promote'])->name('progress.promote');
        
        Route::resource('events', EventController::class);
        Route::post('events/{event}/register', [EventRegistrationController::class, 'register'])->name('events.register');
        Route::post('event-registrations/{registration}/cancel', [EventRegistrationController::class, 'cancel'])->name('event-registrations.cancel');
        Route::get('event-registrations', [EventRegistrationController::class, 'index'])->name('event-registrations.index');
        
        Route::resource('announcements', AnnouncementController::class);
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        
        // Finance
        Route::resource('invoices', OwnerInvoiceController::class);
        Route::resource('payments', OwnerPaymentController::class);
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function() {
            Route::get('/revenue', [OwnerReportController::class, 'revenue'])->name('revenue');
            Route::get('/events', [OwnerReportController::class, 'events'])->name('events');
        });
        
        Route::prefix('classes/{classSchedule}/messages')->name('messages.')->group(function() {
            Route::get('/', [MessageController::class, 'index'])->name('index');
            Route::post('/', [MessageController::class, 'store'])->name('store');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
        });
        
        Route::resource('gallery', GalleryController::class);
        Route::resource('achievements', AchievementController::class);
    });

    // Finance routes
    Route::prefix('finance')->name('finance.')->middleware(['ensure.dojo.access', 'role:finance'])->group(function () {
        Route::get('/dashboard', [FinanceDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('invoices', InvoiceController::class);
        Route::resource('payments', PaymentController::class);
        Route::post('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::resource('memberships', MembershipController::class);
    });

    // Coach routes
    Route::prefix('coach')->name('coach.')->middleware(['ensure.dojo.access', 'role:coach'])->group(function () {
        Route::get('/dashboard', [CoachDashboardController::class, 'index'])->name('dashboard');
        
        // Classes
        Route::get('classes', [CoachClassController::class, 'index'])->name('classes.index');
        Route::get('classes/{classSchedule}', [CoachClassController::class, 'show'])->name('classes.show');
        
        // Attendance
        Route::get('attendance', [CoachAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/create', [CoachAttendanceController::class, 'create'])->name('attendance.create');
        Route::post('attendance', [CoachAttendanceController::class, 'store'])->name('attendance.store');
        Route::post('attendance/bulk', [CoachAttendanceController::class, 'bulkStore'])->name('attendance.bulk-store');
        
        // Students
        Route::get('students', [CoachStudentController::class, 'index'])->name('students.index');
        Route::get('students/{member}', [CoachStudentController::class, 'show'])->name('students.show');
        
        // Events
        Route::get('events', [CoachEventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [CoachEventController::class, 'show'])->name('events.show');
        
        // Progress & Belt Promotion
        Route::get('progress', [CoachProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/{member}', [CoachProgressController::class, 'show'])->name('progress.show');
        Route::post('progress/{member}', [CoachProgressController::class, 'store'])->name('progress.store');
        Route::post('progress/{member}/promote', [CoachProgressController::class, 'promote'])->name('progress.promote');
        
        // Broadcasting
        Route::get('broadcasting', [CoachBroadcastingController::class, 'index'])->name('broadcasting.index');
        Route::post('broadcasting', [CoachBroadcastingController::class, 'store'])->name('broadcasting.store');
    });

    // Student routes
    Route::prefix('student')->name('student.')->middleware(['ensure.dojo.access', 'role:student'])->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('classes', [StudentClassController::class, 'index'])->name('classes.index');
        Route::get('classes/{enrollment}', [StudentClassController::class, 'show'])->name('classes.show');
        Route::get('progress', [StudentProgressController::class, 'index'])->name('progress.index');
        Route::get('payments', [StudentPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{invoice}', [StudentPaymentController::class, 'show'])->name('payments.show');
        Route::get('announcements', [StudentAnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('announcements/{id}', [StudentAnnouncementController::class, 'show'])->name('announcements.show');
    });

    // Parent routes (AUTHENTICATED - NO dojo access restrictions)
    Route::prefix('parent')->name('parent.')->middleware(['role:parent'])->group(function () {
        // All parent routes accessible without dojo check (parent can access all dojos)
        Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
        
        // Child Management
        Route::get('children', [ParentChildController::class, 'index'])->name('children.index');
        Route::get('children/{member}', [ParentChildController::class, 'show'])->name('children.show');
        Route::get('children/{member}/progress', [ParentChildController::class, 'progress'])->name('children.progress');
        
        // Child Registration
        Route::get('register', [ParentRegisterController::class, 'create'])->name('register.create');
        Route::post('register', [ParentRegisterController::class, 'store'])->name('register.store');
        
        // Payment routes
        Route::get('payment/registration/{member}', [ParentPaymentController::class, 'showRegistrationPayment'])->name('payment.registration');
        Route::post('payment/create/{invoice}', [ParentPaymentController::class, 'createPayment'])->name('payment.create');
        
        // Schedules & Events
        Route::get('schedules', [ParentScheduleController::class, 'index'])->name('schedules.index');
        Route::get('events', [ParentEventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [ParentEventController::class, 'show'])->name('events.show');
        Route::post('events/{event}/register', [ParentEventController::class, 'register'])->name('events.register');
        
        // Payments & Invoices
        Route::get('payments', [ParentPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{invoice}', [ParentPaymentController::class, 'show'])->name('payments.show');
    });
});
