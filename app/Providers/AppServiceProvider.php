<?php

namespace App\Providers;

use App\Gateways\SmsGateway;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Validator;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('SmsGateway',function(){
            return new SmsGateway();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend(
            'recaptcha',
            'App\\Validators\\ReCaptcha@validate'
        );
        Paginator::defaultView('vendor.pagination.bootstrap-4');
        require_once (app()->basePath()."/app/Modules/Landing/Http/Helpers/get_url.php");
    }

}
