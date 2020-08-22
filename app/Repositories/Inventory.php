<?php


namespace App\Repositories;


use App\Barang;
use App\Stock;
use App\TransaksiStock;
use Illuminate\Support\Facades\DB;

class Inventory
{
    public function store(Barang $barang, $data)
    {
        DB::beginTransaction();

        $stock = $barang->stocks()->create($data);

        $stock->transaksis()->create([
            "waktu_transaksi" => $stock->tanggal_masuk,
            "jumlah" => $stock->jumlah,
        ]);

        DB::commit();
    }

    public function adjust(Stock $stock, $jumlah, $waktu = null, $entitasTerkait = null)
    {
        DB::beginTransaction();

        $stock->increment("jumlah", $jumlah);

        /** @var TransaksiStock $transaksi */
        $transaksi = $stock->transaksis()->create([
            "waktu_transaksi" => $waktu ?? now(),
            "jumlah" => $jumlah,
        ]);

        if ($entitasTerkait !== null) {
            $transaksi->entitas_terkait()
                ->associate($entitasTerkait)
                ->save();
        }

        DB::commit();
    }

    public function removeByBarang(Barang $barang, $jumlah, $entitasTerkait = null)
    {
        $runningTotal = 0;

        $barang->stocks()
            ->orderBy("tanggal_kadaluarsa")
            ->get()
            ->map(function (Stock $stock) use ($jumlah, &$runningTotal) {
                $to_be_used = 0;

                if ($runningTotal < $jumlah) {
                    $previousRunningTotal = $runningTotal;
                    $runningTotal += $stock->jumlah;
                    $to_be_used = $stock->jumlah;

                    if ($runningTotal >= $jumlah) {
                        $to_be_used = $jumlah - $previousRunningTotal;
                    }
                }

                return [
                    "stock" => $stock,
                    "to_be_used" => $to_be_used,
                ];
            })
            ->filter(function ($stockData) {
                return $stockData["to_be_used"] > 0;
            })
            ->each(function ($stockData) use ($entitasTerkait) {
                /** @var Stock $stock */
                $stock = $stockData["stock"];
                $this->adjust($stock, -$stockData["to_be_used"], null, $entitasTerkait);
            });

        DB::commit();
    }
}