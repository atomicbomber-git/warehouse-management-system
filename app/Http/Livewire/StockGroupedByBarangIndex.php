<?php

namespace App\Http\Livewire;

use App\Barang;
use Livewire\Component;

class StockGroupedByBarangIndex extends Component
{
    public function render()
    {
        return view('livewire.stock-grouped-by-barang-index', [
            "barangs" => Barang::query()
                ->withStock()
                ->paginate()
        ]);
    }
}
