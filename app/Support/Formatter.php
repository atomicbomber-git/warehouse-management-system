<?php


namespace App\Support;


use Carbon\Carbon;
use Illuminate\Support\DateFactory;

class Formatter
{
    public function currency($value)
    {
        return number_format(abs($value));
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

    public function monthAndYear($value)
    {
        /** @var Carbon $date */
        $date = app(DateFactory::class)
            ->make($value);

        return sprintf("Bulan %s Tahun %s", $date->format("F"), $date->format("Y"));
    }

    public function dayAndDate($value)
    {
        return app(DateFactory::class)
            ->make($value)
            ->format("l, d F Y");
    }
}