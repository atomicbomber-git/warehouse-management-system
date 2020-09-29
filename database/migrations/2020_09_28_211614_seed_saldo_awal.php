<?php

use App\SaldoAwal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedSaldoAwal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (SaldoAwal::query()->count() === 0) {
            SaldoAwal::query()->create([
                "jumlah" => 0,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SaldoAwal::query()->delete();
    }
}
