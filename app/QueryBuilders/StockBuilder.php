<?php


namespace App\QueryBuilders;


use App\TransaksiStock;
use Illuminate\Database\Eloquent\Builder;

class StockBuilder extends Builder
{
    public function hasPositiveStock()
    {
        return $this
            ->whereHas("transaksis", function (Builder $builder) {
                $builder->havingRaw("SUM(jumlah) > ?", [0]);
            });
    }

    public function withJumlah()
    {
        return $this->addSelect([
            "jumlah" => TransaksiStock::query()
                ->selectRaw("SUM(jumlah)")
                ->whereColumn("stock_id", "=", "stock.id")
        ]);
    }

    public function withSubtotal($fieldName = "subtotal")
    {
        return $this->addSelect([
            "subtotal" => TransaksiStock::query()
                ->selectRaw("SUM(jumlah) * harga_satuan")
                ->whereColumn("stock_id", "=", "stock.id")
        ]);
    }

    public function withHasAlert($fieldName = "has_alert")
    {
        return $this
            ->selectRaw("DATE(?) >= DATE_SUB(tanggal_kadaluarsa, INTERVAL 1 WEEK) AS $fieldName", [now()]);
    }
}