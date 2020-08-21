<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $table = "penjualan";
    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(ItemPenjualan::class);
    }
}