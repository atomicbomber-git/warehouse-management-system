<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('penjualan_id')->index();
            $table->unsignedInteger('barang_id')->index();
            $table->unsignedDouble('jumlah', 19, 2);
            $table->unsignedDouble('harga_satuan', 19, 2);
            $table->timestamps();
            $table->foreign('penjualan_id')->references('id')->on('penjualan');
            $table->foreign('barang_id')->references('id')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_penjualan');
    }
}
