<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = strtolower($request->get('q', ''));
        
        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'query' => $query
            ]);
        }

        $user = auth()->user();
        $menus = $this->getMenus($user);
        
        $results = collect($menus)->filter(function($menu) use ($query) {
            return str_contains(strtolower($menu['name']), $query) || 
                   (isset($menu['description']) && str_contains(strtolower($menu['description']), $query));
        })->values()->take(10);

        return response()->json([
            'results' => $results,
            'query' => $query
        ]);
    }

    protected function getMenus($user)
    {
        $menus = [];

        if ($user->hasRole('super_admin')) {
            $menus = array_merge($menus, [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home', 'category' => 'Main'],
                ['name' => 'Users', 'url' => route('admin.users.index'), 'icon' => 'users', 'category' => 'Management'],
                ['name' => 'Dojos', 'url' => route('admin.dojos.index'), 'icon' => 'building', 'category' => 'Management'],
                ['name' => 'Members', 'url' => route('admin.members.index'), 'icon' => 'users', 'category' => 'Management'],
                ['name' => 'Instructors', 'url' => route('admin.instructors.index'), 'icon' => 'academic-cap', 'category' => 'Management'],
                ['name' => 'Classes', 'url' => route('admin.classes.monitoring'), 'icon' => 'book-open', 'category' => 'Management'],
                ['name' => 'Events', 'url' => route('admin.events.national'), 'icon' => 'calendar', 'category' => 'Events'],
                ['name' => 'Finance - Payments', 'url' => route('admin.finance.payments'), 'icon' => 'currency-dollar', 'category' => 'Finance'],
                ['name' => 'Finance - Revenue', 'url' => route('admin.finance.revenue-all'), 'icon' => 'chart-bar', 'category' => 'Finance'],
                ['name' => 'Finance - Arrears', 'url' => route('admin.finance.arrears'), 'icon' => 'exclamation-circle', 'category' => 'Finance'],
                ['name' => 'Finance - Cashflow', 'url' => route('admin.finance.cashflow'), 'icon' => 'cash', 'category' => 'Finance'],
                ['name' => 'Curriculum - Styles', 'url' => route('admin.curriculum.styles'), 'icon' => 'tag', 'category' => 'Curriculum'],
                ['name' => 'Curriculum - Levels', 'url' => route('admin.curriculum.levels'), 'icon' => 'chart-bar', 'category' => 'Curriculum'],
                ['name' => 'Curriculum - Belts', 'url' => route('admin.curriculum.belts'), 'icon' => 'trophy', 'category' => 'Curriculum'],
                ['name' => 'Reports - Retention', 'url' => route('admin.reports.retention'), 'icon' => 'chart-pie', 'category' => 'Reports'],
                ['name' => 'Reports - Revenue', 'url' => route('admin.reports.revenue', ['dojo' => 1]), 'icon' => 'chart-bar', 'category' => 'Reports'],
                ['name' => 'Reports - Attendance', 'url' => route('admin.reports.attendance', ['dojo' => 1]), 'icon' => 'user-check', 'category' => 'Reports'],
                ['name' => 'Audit Logs', 'url' => route('admin.audit-logs.index'), 'icon' => 'document-text', 'category' => 'System'],
                ['name' => 'System Settings', 'url' => route('admin.system.settings'), 'icon' => 'cog', 'category' => 'System'],
                ['name' => 'Communication', 'url' => route('admin.communication.announcements'), 'icon' => 'chat-alt', 'category' => 'Communication'],
            ]);
        } elseif ($user->hasRole('owner')) {
            $menus = array_merge($menus, [
                ['name' => 'Dashboard', 'url' => route('owner.dashboard'), 'icon' => 'home', 'category' => 'Main'],
                ['name' => 'Members', 'url' => route('owner.members.index'), 'icon' => 'users', 'category' => 'Management'],
                ['name' => 'Instructors', 'url' => route('owner.instructors.index'), 'icon' => 'academic-cap', 'category' => 'Management'],
                ['name' => 'Classes', 'url' => route('owner.classes.index'), 'icon' => 'book-open', 'category' => 'Classes'],
                ['name' => 'Schedules', 'url' => route('owner.schedules.index'), 'icon' => 'calendar', 'category' => 'Classes'],
                ['name' => 'Enrollments', 'url' => route('owner.enrollments.index'), 'icon' => 'user-add', 'category' => 'Classes'],
                ['name' => 'Attendances', 'url' => route('owner.attendances.index'), 'icon' => 'user-check', 'category' => 'Management'],
                ['name' => 'Ranks', 'url' => route('owner.ranks.index'), 'icon' => 'trophy', 'category' => 'Progress'],
                ['name' => 'Progress', 'url' => route('owner.progress.index'), 'icon' => 'chart-line', 'category' => 'Progress'],
                ['name' => 'Events', 'url' => route('owner.events.index'), 'icon' => 'calendar', 'category' => 'Events'],
                ['name' => 'Event Registrations', 'url' => route('owner.event-registrations.index'), 'icon' => 'clipboard-list', 'category' => 'Events'],
                ['name' => 'Announcements', 'url' => route('owner.announcements.index'), 'icon' => 'megaphone', 'category' => 'Communication'],
                ['name' => 'Notifications', 'url' => route('owner.notifications.index'), 'icon' => 'bell', 'category' => 'Communication'],
                ['name' => 'Gallery', 'url' => route('owner.gallery.index'), 'icon' => 'photograph', 'category' => 'Content'],
                ['name' => 'Achievements', 'url' => route('owner.achievements.index'), 'icon' => 'star', 'category' => 'Content'],
            ]);
        } elseif ($user->hasRole('finance')) {
            $menus = array_merge($menus, [
                ['name' => 'Dashboard', 'url' => route('finance.dashboard'), 'icon' => 'home', 'category' => 'Main'],
                ['name' => 'Invoices', 'url' => route('finance.invoices.index'), 'icon' => 'document-text', 'category' => 'Finance'],
                ['name' => 'Payments', 'url' => route('finance.payments.index'), 'icon' => 'currency-dollar', 'category' => 'Finance'],
                ['name' => 'Memberships', 'url' => route('finance.memberships.index'), 'icon' => 'credit-card', 'category' => 'Finance'],
            ]);
        } elseif ($user->hasRole('coach')) {
            $menus = array_merge($menus, [
                ['name' => 'Dashboard', 'url' => route('coach.dashboard'), 'icon' => 'home', 'category' => 'Main'],
                ['name' => 'Classes', 'url' => route('coach.classes.index'), 'icon' => 'book-open', 'category' => 'Classes'],
                ['name' => 'Progress', 'url' => route('coach.progress.index'), 'icon' => 'chart-line', 'category' => 'Progress'],
            ]);
        } elseif ($user->hasRole('student')) {
            $menus = array_merge($menus, [
                ['name' => 'Dashboard', 'url' => route('student.dashboard'), 'icon' => 'home', 'category' => 'Main'],
                ['name' => 'Classes', 'url' => route('student.classes.index'), 'icon' => 'book-open', 'category' => 'Classes'],
                ['name' => 'Progress', 'url' => route('student.progress.index'), 'icon' => 'chart-line', 'category' => 'Progress'],
                ['name' => 'Payments', 'url' => route('student.payments.index'), 'icon' => 'currency-dollar', 'category' => 'Payments'],
                ['name' => 'Announcements', 'url' => route('student.announcements.index'), 'icon' => 'megaphone', 'category' => 'Communication'],
            ]);
        } elseif ($user->hasRole('parent')) {
            $menus = array_merge($menus, [
                ['name' => 'Dashboard', 'url' => route('parent.dashboard'), 'icon' => 'home', 'category' => 'Main'],
                ['name' => 'Children', 'url' => route('parent.children.index'), 'icon' => 'users', 'category' => 'Family'],
                ['name' => 'Schedules', 'url' => route('parent.schedules.index'), 'icon' => 'calendar', 'category' => 'Schedules'],
                ['name' => 'Events', 'url' => route('parent.events.index'), 'icon' => 'calendar', 'category' => 'Events'],
                ['name' => 'Payments', 'url' => route('parent.payments.index'), 'icon' => 'currency-dollar', 'category' => 'Payments'],
            ]);
        }

        return $menus;
    }
}

