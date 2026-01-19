<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        
        // Force HTTPS disabled for local development
        // Uncomment below if needed for production:
        /*
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }
        */
    }
}
