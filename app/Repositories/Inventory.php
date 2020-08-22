<?php


namespace App\Repositories;


use App\Barang;
use App\Stock;
use App\TransaksiStock;
use Illuminate\Support\Facades\DB;

class Inventory
{
    public function purchaseBarang(Barang $barang, $data)
    {
        DB::beginTransaction();

        /** @var Stock $stock */
        $stock = $barang->stocks()->create($data);

        $transaksiStock = $this->adjust($stock, $stock->jumlah, $data["tanggal_masuk"]);

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => $data["tanggal_masuk"],
            "jumlah" => -$transaksiStock->stock()
                ->selectRaw("jumlah * harga_satuan as subtotal")
                ->value("subtotal"),
        ]);

        DB::commit();
    }

    public function returnStock(Stock $stock)
    {
        $originalStockSubtotal = Stock::query()
            ->where("id", $stock->id)
            ->selectRaw("jumlah * harga_satuan AS subtotal")
            ->value("subtotal");

        $transaksiStock = $this->adjust($stock, $stock->jumlah);

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => today(),
            "jumlah" => $originalStockSubtotal,
        ]);
    }

    public function adjust(Stock $stock, $jumlah, $waktu = null, $entitasTerkait = null)
    {
        DB::beginTransaction();

        $stock->increment("jumlah", $jumlah);

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