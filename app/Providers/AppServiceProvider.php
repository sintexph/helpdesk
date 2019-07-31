<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\FileUpload;
use App\Ticket;
use App\User;
use App\Observers\UploadObserver;
use App\Observers\TicketObserver;
use App\Observers\UserObserver;
use App\Helpers\TrackRequestHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
   
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FileUpload::observe(UploadObserver::class);
        Ticket::observe(TicketObserver::class);
        User::observe(UserObserver::class);   
    }
}
