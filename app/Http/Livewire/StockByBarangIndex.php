<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StockByBarangIndex extends Component
{
    private $barangId;

    public function mount($barangId)
    {
        $this->barangId = $barangId;
    }

    public function render()
    {
//        return view('livewire.stock-by-barang-index', [
//
//        ]);
    }
}
