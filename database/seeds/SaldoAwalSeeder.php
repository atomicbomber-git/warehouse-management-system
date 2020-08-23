<?php

use App\SaldoAwal;
use Illuminate\Database\Seeder;

class SaldoAwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SaldoAwal::query()->create([
            "jumlah" => 1000000,
        ]);
    }
}
