<?php


namespace App\Helpers;


class Phone
{
    public static function formatCorrected($phone)
    {
       return str_replace('+', '', $phone);
    }
}
