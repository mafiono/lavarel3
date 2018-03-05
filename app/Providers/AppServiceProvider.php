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
            $isValid = RulesValidator::validaNIF($value);
            $isUnique = RulesValidator::isNifUnique($value);
            $isNotPt = ($validator->getData()['nationality'] ?? '') !== 'PT';
            if ($isValid && $isUnique)
                return true;
            return $isNotPt;
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
        Validator::extend('zip_code', function($attribute, $value, $parameters, $validator) {
            if (preg_match('/^[0-9]{4}-[0-9]{3}$/', $value))
                return true;

            return $validator->getData()['country'] !== 'PT';
        });
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            $testPhone = str_replace(array(' ', '+'), array('', '00'), $value ?? '');
            return preg_match('/^\d{6,22}$/', $testPhone);
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
