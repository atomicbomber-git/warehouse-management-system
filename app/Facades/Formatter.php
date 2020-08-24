<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/** @mixin \App\Support\Formatter */
class Formatter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Support\Formatter::class;
    }
}