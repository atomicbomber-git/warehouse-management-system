<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Penjualan;
use Faker\Generator as Faker;

$factory->define(Penjualan::class, function (Faker $faker) {
    return [
        "waktu_penjualan" => now()->subDays(rand(
             0,
            3 * 30
        ))
    ];
});
