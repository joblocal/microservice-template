<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services
     */
    public function boot()
    {
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
        //
    }
}
