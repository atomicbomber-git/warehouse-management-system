<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransaksiStock extends Model
{
    protected $table = "transaksi_stock";
    protected $guarded = [];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function transaksi_keuangan(): MorphOne
    {
        return $this->morphOne(TransaksiKeuangan::class, "entitas_terkait");
    }

    public function entitas_terkait(): MorphTo
    {
        return $this->morphTo();
    }
}
