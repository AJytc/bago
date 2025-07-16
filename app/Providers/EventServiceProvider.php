<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot(): void
    {
        parent::boot();

        Event::listen(Login::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->withProperties([
                    'role' => $event->user->getRoleNames()->first() ?? 'unknown',
                ])
                ->log('logged in');
        });

        Event::listen(Logout::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->withProperties([
                    'role' => $event->user->getRoleNames()->first() ?? 'unknown',
                ])
                ->log('logged out');
        });
    }
}
