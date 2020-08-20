<?php

namespace App\Http\Livewire;

use App\Barang;
use App\Constants\MessageState;
use App\Stock;
use App\Support\SessionHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;

class StockByBarangIndex extends Component
{
    use WithPagination;

    public $barangId;

    protected $listeners = [
        "stock:delete" => "deleteStock"
    ];

    public function mount($barangId)
    {
        $this->barangId = $barangId;
    }

    public function deleteStock($stockId)
    {
        try {
            Stock::query()
                ->where("id", $stockId)
                ->delete();

            SessionHelper::flashMessage(
                __("messages.delete.success"),
                MessageState::STATE_SUCCESS,
            );
        } catch (\Throwable $throwable) {
            SessionHelper::flashMessage(
                __("messages.delete.failure"),
                MessageState::STATE_DANGER,
            );
        }

    }

    public function getBarangProperty(): Model
    {
        return Barang::query()
            ->findOrFail($this->barangId);
    }

    public function getStocksProperty(): LengthAwarePaginator
    {
        return $this->getBarangProperty()->stocks()
            ->withSubtotal()
            ->orderByDesc("tanggal_masuk")
            ->with("pemasok")
            ->paginate();
    }

    public function render()
    {
        return view('livewire.stock-by-barang-index', [
            "barang" => $this->getBarangProperty(),
            "stocks" => $this->getStocksProperty(),
        ]);
    }
}
