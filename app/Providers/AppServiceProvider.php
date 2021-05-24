<?php

namespace App\Providers;

use App\Models\SmsCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('phone_number', function ($attribute, $value, $parameters) {


            if (substr($value, 0, 1) == '7' && strlen($value) == 11) {
                return true;
            }
            if (substr($value, 0, 2) == "+7" && strlen($value) == 12) {
                return true;
            }
            if (substr($value, 0, 1) == '8' && strlen($value) == 11) {
                return true;
            }
            return false;
        }, 'Неверный формат номера телефона');

        Validator::extend('code', function ($attribute, $value, $parameters) {
            if (!empty($parameters)) {
                $count = SmsCode::where('phone', $parameters[0])
                    ->where('code', $value)
                    ->first();

                if (!empty($count)) {
                    $count->delete();
                    return true;
                }

                $sms = SmsCode::where('phone', $parameters[0])->first();
                if (!empty($sms)) {
                    $sms->try = $sms->try += 1;
                    $sms->save();
                }
            }
            return false;
        }, 'Неправильно ввели код подтверждения');

        Validator::extend('code_try', function ($attribute, $value, $parameters) {
            if (!empty($parameters)) {
                $count = SmsCode::where('phone', $parameters[0])
                    ->where('code', $value)
                    ->first();

                if (!empty($count)) {
                    $count->delete();
                    return true;
                }
                $sms = SmsCode::where('phone', $parameters[0])->first();
                if (!empty($sms)) {
                    if ($sms->try >= 3) {
                        $sms->delete();
                        return false;
                    }
                }
            }

            return true;
        }, 'Слишком много попыток, попробуйте позже');
    }
}
