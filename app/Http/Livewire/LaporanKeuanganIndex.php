<?php

namespace App\Http\Livewire;

use App\TransaksiStock;
use Livewire\Component;

class LaporanKeuanganIndex extends Component
{
    public function render()
    {
        return view('livewire.laporan-keuangan-index', [
            "transaksis" => TransaksiStock::query()
                ->with([
                    "stock.barang"
                ])
                ->paginate()
        ]);
    }
}
