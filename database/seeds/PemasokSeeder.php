<?php

use App\Pemasok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemasokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        factory(Pemasok::class, 30)
            ->create();

        DB::commit();
    }
}
