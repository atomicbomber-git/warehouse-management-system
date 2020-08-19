<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Barang;
use Faker\Generator as Faker;

$factory->define(Barang::class, function (Faker $faker) {
    return [
        'nama' => $faker->ingredient,
        'satuan' => $faker->randomElement(['bungkus', 'kotak', 'kg', 'kardus']),
        'harga_jual' => rand(1, 200) * 5000
    ];
});
