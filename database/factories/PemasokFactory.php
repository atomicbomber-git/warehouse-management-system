<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pemasok;
use Faker\Generator as Faker;

$factory->define(Pemasok::class, function (Faker $faker) {
    return [
        "nama" => $faker->company,
        "alamat" => $faker->address,
        "no_telepon" => $faker->phoneNumber,
    ];
});
