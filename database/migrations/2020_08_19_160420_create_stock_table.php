<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('barang_id')->index();
            $table->unsignedInteger('pemasok_id')->index();
            $table->unsignedDouble('harga_satuan', 19, 2);
            $table->date('tanggal_masuk')->index();
            $table->date('tanggal_kadaluarsa')->index();
            $table->foreign('barang_id')->references('id')->on('barang');
            $table->foreign('pemasok_id')->references('id')->on('pemasok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
    }
}
