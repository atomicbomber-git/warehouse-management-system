<?php

namespace App\Http\Livewire;

use App\ItemPenjualan;
use App\TransaksiKeuangan;
use App\TransaksiStock;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Livewire\Component;

class LaporanKeuanganIndex extends Component
{
    public function render()
    {
        return view('livewire.laporan-keuangan-index', [
            "transaksis" => TransaksiKeuangan::query()
                ->latest()
                ->with([
                    "entitas_terkait" => function (MorphTo $morphTo) {
                        $morphTo->morphWith([
                            TransaksiStock::class => "stock.barang",
                            ItemPenjualan::class => "barang",
                        ]);
                    }
                ])
                ->paginate()
        ]);
    }
}
