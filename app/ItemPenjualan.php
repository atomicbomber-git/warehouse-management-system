<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ItemPenjualan extends Model
{
    protected $table = "item_penjualan";
    protected $guarded = [];

    public function transaksi_stocks(): MorphMany
    {
        return $this->morphMany(TransaksiStock::class, "entitas_terkait");
    }
}
