<?php

namespace App;

use App\QueryBuilders\BarangBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = "barang";
    protected $guarded = [];

    public static function query(): BarangBuilder
    {
        return parent::query();
    }

    /** return BarangBuilder */
    public function newEloquentBuilder($query)
    {
        return new BarangBuilder($query);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
