<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = "stock";
    protected $guarded = [];

    public function scopeWithSubtotal(Builder $builder, $fieldName = "subtotal")
    {
        $builder->select("*")
            ->selectRaw("harga_satuan * jumlah AS {$fieldName}");
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class);
    }
}
