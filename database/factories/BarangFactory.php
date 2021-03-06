<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Barang;
use Faker\Generator as Faker;

$factory->define(Barang::class, function (Faker $faker) {
    return [
        'nama' => $faker->unique()->ingredient,
        'satuan' => $faker->randomElement(['Bungkus', 'Kotak', 'Kg', 'Kardus']),
        'harga_jual' => rand(1, 200) * 5000
    ];
});
