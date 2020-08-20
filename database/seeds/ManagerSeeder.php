<?php

use App\Constants\UserLevel;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usernameOrPassword = "admin";

        User::query()->create([
            "name" => "Manager",
            "username" => $usernameOrPassword,
            "password" => Hash::make($usernameOrPassword),
            "level" => UserLevel::MANAGER,
        ]);
    }
}
