<?php

use App\Barang;
use App\Constants\StockStatus;
use App\Pemasok;
use App\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $pemasoks = Pemasok::query()->get();
        $barangs = Barang::query()->get();

        foreach ($pemasoks as $pemasok) {
            foreach ($barangs as $barang) {
                if (rand(0, 3) === 0) {
                    continue;
                }

                $tanggalMasuk = now();
                $tanggalKadaluarsa = $tanggalMasuk->clone()->addDays(rand(0, 14));

                /** @var Stock $stock */
                $stock = Stock::query()->create([
                    "barang_id" => $barang->id,
                    "pemasok_id" => $pemasok->id,
                    "jumlah" => rand(1, 10),
                    "harga_satuan" => rand(5, 20) * 500,
                    "tanggal_masuk" => $tanggalMasuk,
                    "tanggal_kadaluarsa" => $tanggalKadaluarsa,
                ]);

                $stock->transaksis()->create([
                    "waktu_transaksi" => now(),
                    "jumlah" => $stock->jumlah,
                ]);
            }
        }

        DB::commit();
    }
}
