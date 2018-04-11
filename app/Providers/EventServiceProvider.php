<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Queue\Events\JobFailed' => [
            'App\Listeners\JobFailedEventListener',
        ],
        'Illuminate\Queue\Events\JobProcessed' => [
            'App\Listeners\JobProcessedEventListener',
        ],
    ];
}
