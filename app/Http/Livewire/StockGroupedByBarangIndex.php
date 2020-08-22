<?php

namespace App\Http\Livewire;

use App\Barang;
use Livewire\Component;
use Livewire\WithPagination;

class StockGroupedByBarangIndex extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.stock-grouped-by-barang-index', [
            "barangs" => Barang::query()
                ->withStock()
                ->withHasAlert()
                ->orderBy("nama")
                ->paginate()
        ]);
    }
}
