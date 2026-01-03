<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Member;
use App\Policies\MemberPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Member::class => MemberPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Member::class, MemberPolicy::class);
    }
}
