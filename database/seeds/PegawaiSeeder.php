<?php

use App\Constants\UserLevel;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        factory(User::class, 100)
            ->create([
                "level" => UserLevel::PEGAWAI
            ]);

        DB::commit();
    }
}
