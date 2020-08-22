<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ItemPenjualan extends Model
{
    protected $table = "item_penjualan";
    protected $guarded = [];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function transaksi_keuangan(): MorphOne
    {
        return $this->morphOne(TransaksiKeuangan::class, "entitas_terkait");
    }

    public function transaksi_stocks(): MorphMany
    {
        return $this->morphMany(TransaksiStock::class, "entitas_terkait");
    }
}
