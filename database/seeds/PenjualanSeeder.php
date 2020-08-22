<?php

use App\Barang;
use App\Constants\UserLevel;
use App\Penjualan;
use App\Repositories\Inventory;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barangs = Barang::query()
            ->get();

        $pegawais = User::query()
            ->where("level", UserLevel::PEGAWAI)
            ->get();

        /** @var Inventory $inventory */
        $inventory = app(Inventory::class);

        DB::beginTransaction();

         factory(Penjualan::class, 100)
            ->create([
                "user_id" => $pegawais->random()->id,
            ])
            ->each(function (Penjualan $penjualan) use ($inventory, $barangs) {
                 $barangs
                    ->shuffle()
                    ->take(rand(1, 6))
                    ->each(function (Barang $barang) use ($inventory, $penjualan) {
                        $jumlah = rand(1, 10);

                        $itemPenjualan = $penjualan->items()->create([
                            "barang_id" => $barang->id,
                            "jumlah" => $jumlah,
                            "harga_satuan" => $barang->harga_jual,
                        ]);

                        $inventory->removeByBarang($barang, $jumlah, $itemPenjualan);
                    });
            });

        DB::commit();
    }
}
