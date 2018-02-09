<?php

namespace App\Providers;

use App, Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('nif', function($attribute, $value, $parameters, $validator) {
            return RulesValidator::validaNIF($value);
        });
        Validator::extend('captcha', function($attribute, $value, $parameters, $validator) {
            return strtolower(Session::get('captcha.code')) === strtolower($value);
        });
        Validator::extend('iban', function($attribute, $value, $parameters, $validator) {
            return RulesValidator::checkIBAN($value);
        });
        Validator::extend('cc', function($attribute, $value, $parameters, $validator) {
            return RulesValidator::ValidateNumeroDocumento($value);
        });
        Validator::extend('unique_cc', function($attribute, $value, $parameters, $validator) {
            return RulesValidator::isDocumentUnique($value);
        });
        Validator::extend('unique_tax', function($attribute, $value, $parameters, $validator) {
            return RulesValidator::isNifUnique($value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ip', function () {
            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if (isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if (isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if (isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';

            return $ipaddress;
        });
    }

}
