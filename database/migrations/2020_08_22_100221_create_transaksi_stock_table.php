<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_stock', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('waktu_transaksi');
            $table->unsignedInteger('stock_id')->index();
            $table->integer('jumlah');

            $table->timestamps();
            $table->foreign('stock_id')->references('id')->on('stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_stock');
    }
}
