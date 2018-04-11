<?php

namespace App\Providers;

use App\Models\EventLog;
use App\Queue\SnsSqsConnector;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services
     */
    public function boot()
    {
        EventLog::saving(function (EventLog $eventLog) {
            if (!$eventLog->isValid()) {
                return false;
            }
        });

        Validator::extend('class', function ($attribute, $value, $parameters, $validator) {
            return get_class($value) == array_shift($parameters);
        });

        Validator::replacer('class', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':class', array_shift($parameters), $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
