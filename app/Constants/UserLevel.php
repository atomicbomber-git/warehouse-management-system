<?php


namespace App\Constants;


class UserLevel
{
    const MANAGER = "MANAGER";
    const PEGAWAI = "PEGAWAI";

    const LEVELS = [
        UserLevel::PEGAWAI => "Pegawai",
        UserLevel::MANAGER => "Manager",
    ];
}