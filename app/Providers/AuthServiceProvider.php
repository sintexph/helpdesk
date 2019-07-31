<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Ticket::class => \App\Policies\TicketPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('support', function ($user) {
            return $user->roleIs(\App\Helpers\UserRole::SUPPORT);
        });
        Gate::define('admin', function ($user) {
            return $user->roleIs(\App\Helpers\UserRole::ADMINISTRATOR);
        });
        Gate::define('administrator', function ($user) {
            return $user->roleIs(\App\Helpers\UserRole::ADMINISTRATOR);
        });
        Gate::define('moderator', function ($user) {
            return $user->roleIs(\App\Helpers\UserRole::MODERATOR);
        });
        Gate::define('sender', function ($user) {
            return $user->roleIs(\App\Helpers\UserRole::SENDER);
        });


        Gate::define('manage-ticket', function ($user) {
            return $user->roleIs(\App\Helpers\UserRole::ADMINISTRATOR) || $user->roleIs(\App\Helpers\UserRole::MODERATOR);
        });
    }
}
