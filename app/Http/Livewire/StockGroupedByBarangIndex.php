<?php

namespace App\Http\Livewire;

use App\Barang;
use App\Stock;
use Livewire\Component;
use Livewire\WithPagination;

class StockGroupedByBarangIndex extends Component
{
    use WithPagination;

    /**
     * @var false|mixed
     */
    public $inGuestMode;

    public function mount($inGuestMode = false)
    {
        $this->inGuestMode = $inGuestMode;
    }

    public function render()
    {
        return view('livewire.stock-grouped-by-barang-index', [
            "barangs" => Barang::query()
                ->addSelect([
                    "has_stock" => Stock::query()
                        ->join("transaksi_stock", "transaksi_stock.stock_id", "=", "stock.id")
                        ->selectRaw("COALESCE(SUM(jumlah), 0) > 150")
                        ->whereColumn("barang.id", "=", "stock.barang_id")
                ])
                ->withStock()
                ->withHasAlert()
                ->orderBy("nama")
                ->paginate()
        ]);
    }
}
