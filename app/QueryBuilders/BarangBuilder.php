<?php


namespace App\QueryBuilders;


use App\Stock;
use Illuminate\Database\Eloquent\Builder;

class BarangBuilder extends Builder
{
    public function withStock($fieldName = "stock")
    {
        return $this
            ->addSelect([
                $fieldName => Stock::query()
                    ->join("transaksi_stock", "transaksi_stock.stock_id", "=", "stock.id")
                    ->selectRaw("COALESCE(SUM(jumlah), 0)")
                    ->whereColumn("barang.id", "=", "stock.barang_id")
            ]);
    }

    public function withHasAlert($fieldName = "has_alert", $threshold = 5)
    {
        return $this
            ->addSelect([
                $fieldName => Stock::query()
                    ->join("transaksi_stock", "transaksi_stock.stock_id", "=", "stock.id")
                    ->selectRaw("COALESCE(SUM(jumlah), 0) < ?", [$threshold])
                    ->whereColumn("barang.id", "=", "stock.barang_id")
            ]);
    }
}