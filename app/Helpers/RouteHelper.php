<?php

namespace App\Helpers;

class RouteHelper
{
    public static function safeRoute($name, $parameters = [], $absolute = true)
    {
        try {
            return route($name, $parameters, $absolute);
        } catch (\Illuminate\Routing\Exceptions\RouteNotFoundException $e) {
            return '#';
        }
    }
}