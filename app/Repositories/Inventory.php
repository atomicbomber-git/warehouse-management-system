<?php


namespace App\Repositories;


use App\Barang;
use App\Stock;
use App\TransaksiStock;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Inventory
{
    public function purchaseBarang(Barang $barang, $data)
    {
        DB::beginTransaction();

        /** @var Stock $stock */
        $stock = $barang->stocks()->create(Arr::except($data, "jumlah"));

        $transaksiStock = $this->adjust(
            $stock,
            $data["jumlah"],
            $data["tanggal_masuk"]
        );

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => $data["tanggal_masuk"],
            "jumlah" => -Stock::query()
                ->whereKey($stock->id)
                ->withSubtotal()
                ->value("subtotal"),
        ]);

        DB::commit();
    }

    public function returnStock(Stock $stock)
    {
        $originalStock = Stock::query()
            ->whereKey( $stock->id)
            ->withJumlah()
            ->withSubtotal()
            ->first();

        $transaksiStock = $this->adjust($stock, -$originalStock->jumlah);

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => today(),
            "jumlah" => $originalStock->subtotal,
        ]);
    }

    public function adjust(Stock $stock, $jumlah, $waktu = null, $entitasTerkait = null)
    {
        DB::beginTransaction();

        /** @var TransaksiStock $transaksiStock */
        $transaksiStock = $stock->transaksis()->create([
            "tanggal_transaksi" => $waktu ?? now(),
            "jumlah" => $jumlah,
        ]);

        if ($entitasTerkait !== null) {
            $transaksiStock->entitas_terkait()
                ->associate($entitasTerkait)
                ->save();
        }

        DB::commit();

        return $transaksiStock;
    }

    public function removeStockByBarang(Barang $barang, $jumlah, $entitasTerkait = null)
    {
        $runningTotal = 0;

        Stock::query()
            ->where("barang_id", $barang->id)
            ->withJumlah()
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