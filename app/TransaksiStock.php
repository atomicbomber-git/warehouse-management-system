<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransaksiStock extends Model
{
    protected $table = "transaksi_stock";
    protected $guarded = [];

    public function entitas_terkait(): MorphTo
    {
        return $this->morphTo();
    }
}
