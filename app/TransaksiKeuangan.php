<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransaksiKeuangan extends Model
{
    protected $table = "transaksi_keuangan";
    protected $guarded = [];

    public function entitas_terkait(): MorphTo
    {
        return $this->morphTo();
    }
}
