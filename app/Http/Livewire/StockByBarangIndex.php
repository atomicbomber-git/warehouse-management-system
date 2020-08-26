<?php

namespace App\Http\Livewire;

use App\Barang;
use App\Constants\MessageState;
use App\Repositories\Inventory;
use App\Stock;
use App\Support\SessionHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;

class StockByBarangIndex extends Component
{
    use WithPagination;

    public $barangId;
    public $inGuestMode;

    protected $listeners = [
        "stock:delete" => "deleteStock"
    ];


    public function mount($barangId, $inGuestMode = false)
    {
        $this->barangId = $barangId;
        $this->inGuestMode = $inGuestMode;
    }

    public function deleteStock($stockId, Inventory $inventory)
    {
        try {
            /** @var Stock $stock */
            $stock = Stock::query()
                ->findOrFail($stockId);

            $inventory->returnStock($stock);

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
        return Stock::query()
            ->where("barang_id", $this->barangId)
            ->select("*")
            ->hasPositiveStock()
            ->withJumlah()
            ->withSubtotal()
            ->withHasAlert()
            ->with("pemasok")
            ->orderByDesc("tanggal_masuk")
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
