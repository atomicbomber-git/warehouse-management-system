<?php


namespace App\QueryBuilder;


use App\Stock;
use Illuminate\Database\Eloquent\Builder;

class BarangBuilder extends Builder
{
    public function withStock($fieldName = "stock")
    {
        return $this
            ->select(["*"])
            ->addSelect([
                $fieldName => Stock::query()
                    ->selectRaw("COALESCE(SUM(jumlah), 0)")
                    ->whereColumn("barang.id", "=", "stock.barang_id")
            ]);
    }
}