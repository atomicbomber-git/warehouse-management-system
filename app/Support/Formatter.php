<?php


namespace App\Support;


use Illuminate\Support\DateFactory;

class Formatter
{
    public function currency($value)
    {
        return "Rp. " . number_format($value);
    }

    public function number($value)
    {
        return number_format($value);
    }

    public function timeWithoutSeconds($value)
    {
        return app(DateFactory::class)
            ->createFromFormat("H:i:s", $value)
            ->format("H:i");
    }

    public function date($value)
    {
        return app(DateFactory::class)
            ->make($value)
            ->format("d F Y");
    }

    public function dayAndDate($value)
    {
        return app(DateFactory::class)
            ->make($value)
            ->format("l, d F Y");
    }
}