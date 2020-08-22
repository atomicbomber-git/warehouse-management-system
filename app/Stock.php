<?php

namespace App;

use App\QueryBuilders\BarangBuilder;
use App\QueryBuilders\StockBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Stock extends Model
{
    protected $table = "stock";
    protected $guarded = [];

    public static function query(): StockBuilder
    {
        return parent::query();
    }

    /** return StockBuilder */
    public function newEloquentBuilder($query)
    {
        return new StockBuilder($query);
    }

    public function scopeWithSubtotal(Builder $builder, $fieldName = "subtotal")
    {
        $builder->select("*")
            ->selectRaw("harga_satuan * jumlah AS {$fieldName}");
    }

    public function transaksi_keuangan(): MorphOne
    {
        return $this->morphOne(TransaksiKeuangan::class, "entitas_terkait");
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(TransaksiStock::class);
    }
}
