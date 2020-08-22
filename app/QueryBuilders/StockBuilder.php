<?php


namespace App\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StockBuilder extends Builder
{
    public function withSubtotal($fieldName = "subtotal")
    {
        return $this
            ->selectRaw("harga_satuan * jumlah AS $fieldName");
    }

    public function withHasAlert($fieldName = "has_alert", $threshold = null)
    {
        return $this
            ->selectRaw("DATE(?) >= DATE_SUB(tanggal_kadaluarsa, INTERVAL 1 WEEK) AS $fieldName", [$threshold ?? now()]);
    }
}