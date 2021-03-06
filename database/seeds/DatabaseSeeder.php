<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ManagerSeeder::class);
         $this->call(PegawaiSeeder::class);
         $this->call(BarangSeeder::class);
         $this->call(PemasokSeeder::class);
         $this->call(StockSeeder::class);
//         $this->call(SaldoAwalSeeder::class);
//         $this->call(PenjualanSeeder::class);
    }
}
