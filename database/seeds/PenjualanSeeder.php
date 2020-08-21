<?php

use App\Barang;
use App\Constants\UserLevel;
use App\Penjualan;
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

        DB::beginTransaction();

         factory(Penjualan::class, 100)
            ->create([
                "user_id" => $pegawais->random()->id,
            ])
            ->each(function (Penjualan $penjualan) use ($barangs) {
                $penjualan->items()->createMany(
                    $barangs
                        ->shuffle()
                        ->take(rand(1, 6))
                        ->map(function (Barang $barang) {
                            return [
                                "barang_id" => $barang->id,
                                "jumlah" => rand(1, 10),
                                "harga_satuan" => $barang->harga_jual,
                            ];
                        })
                );
            });

        DB::commit();
    }
}
