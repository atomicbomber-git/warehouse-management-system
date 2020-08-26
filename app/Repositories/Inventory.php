<?php


namespace App\Repositories;


use App\Barang;
use App\Constants\AlasanTransaksi;
use App\Stock;
use App\TransaksiStock;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Inventory
{
    public function purchaseBarang(Barang $barang, $data)
    {
        DB::beginTransaction();

        dd($data);

        /** @var Stock $stock */
        $stock = $barang->stocks()->create(Arr::except($data, "jumlah"));

        $transaksiStock = $this->adjust(
            $stock,
            $data["jumlah"],
            [
                "waktu" => $data["tanggal_masuk"],
                "alasan" => AlasanTransaksi::PEMBELIAN,
            ]
        );

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => $data["tanggal_masuk"],
            "alasan" => AlasanTransaksi::PEMBELIAN,
            "jumlah" => -Stock::query()
                ->whereKey($stock->id)
                ->withSubtotal()
                ->value("subtotal"),
        ]);

        DB::commit();
    }

    public function returnStock(Stock $stock, $amount = null)
    {
        /** @var Stock $originalStock */
        $originalStock = Stock::query()
            ->whereKey($stock->id)
            ->withJumlah()
            ->first();

        $transaksiStock = $this->adjust($originalStock, -($amount ?? $originalStock->jumlah), [
            "alasan" => AlasanTransaksi::PENGEMBALIAN,
        ]);

        $subtotal = TransaksiStock::query()
            ->join("stock", "stock.id", "=", "stock_id")
            ->whereKey($transaksiStock->id)
            ->selectRaw("jumlah * harga_satuan AS subtotal")
            ->value("subtotal");

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => today(),
            "alasan" => AlasanTransaksi::PENGEMBALIAN,
            "jumlah" => abs($subtotal),
        ]);
    }

    public function throwAwayStock(Stock $stock, $amount = null)
    {
        /** @var Stock $originalStock */
        $originalStock = Stock::query()
            ->whereKey($stock->id)
            ->withJumlah()
            ->first();

        $transaksiStock = $this->adjust($originalStock, -($amount ?? $originalStock->jumlah), [
            "alasan" => AlasanTransaksi::PEMBUANGAN,
        ]);

        $subtotal = TransaksiStock::query()
            ->join("stock", "stock.id", "=", "stock_id")
            ->whereKey($transaksiStock->id)
            ->selectRaw("jumlah * harga_satuan AS subtotal")
            ->value("subtotal");

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => today(),
            "alasan" => AlasanTransaksi::PEMBUANGAN,
            "jumlah" => -abs($subtotal),
        ]);
    }

    public function sellStock(Stock $stock, $amount = null)
    {
        /** @var Stock $originalStock */
        $originalStock = Stock::query()
            ->whereKey($stock->id)
            ->withJumlah()
            ->first();

        $transaksiStock = $this->adjust($originalStock, -($amount ?? $originalStock->jumlah), [
            "alasan" => AlasanTransaksi::PENJUALAN,
        ]);

        $subtotal = TransaksiStock::query()
            ->join("stock", "stock.id", "=", "stock_id")
            ->whereKey($transaksiStock->id)
            ->selectRaw("jumlah * harga_satuan AS subtotal")
            ->value("subtotal");

        $transaksiStock->transaksi_keuangan()->create([
            "tanggal_transaksi" => today(),
            "alasan" => AlasanTransaksi::PENJUALAN,
            "jumlah" => +abs($subtotal),
        ]);
    }

    public function adjust(Stock $stock, $jumlah, $options = [])
    {
        DB::beginTransaction();

        /** @var TransaksiStock $transaksiStock */
        $transaksiStock = $stock->transaksis()->create([
            "tanggal_transaksi" => $options["waktu"] ?? now(),
            "jumlah" => $jumlah,
            "alasan" => $options["alasan"] ?? ($jumlah > 0 ? AlasanTransaksi::PEMBELIAN : AlasanTransaksi::PENJUALAN)
        ]);

        if (($options["entitas_terkait"] ?? null) !== null) {
            $transaksiStock->entitas_terkait()
                ->associate($options["entitas_terkait"])
                ->save();
        }

        DB::commit();

        return $transaksiStock;
    }

    public function removeStockByBarang(Barang $barang, $jumlah, $options)
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
            ->each(function ($stockData) use ($options) {
                /** @var Stock $stock */
                $stock = $stockData["stock"];
                $this->adjust($stock, -$stockData["to_be_used"], array_merge([
                    "alasan" => AlasanTransaksi::PENJUALAN,
                ], $options));
            });

        DB::commit();
    }
}