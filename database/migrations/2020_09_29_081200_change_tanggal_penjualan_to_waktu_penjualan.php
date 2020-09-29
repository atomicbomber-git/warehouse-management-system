<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTanggalPenjualanToWaktuPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dateTime('waktu_penjualan')->change();
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->renameColumn('waktu_penjualan', 'waktu_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->renameColumn('waktu_penjualan', 'waktu_penjualan');
        });

        Schema::table('penjualan', function (Blueprint $table) {
            $table->date('waktu_penjualan')->change();
        });
    }
}
