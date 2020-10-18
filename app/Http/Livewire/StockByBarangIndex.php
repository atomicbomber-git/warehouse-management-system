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
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class StockByBarangIndex extends Component
{
    use WithPagination;

    public $barangId;
    public $inGuestMode;
    public $filter;

    const FILTER_SHOW_ONLY_EXPIRED_AND_RUN_OUT = "only-expired-and-run-out";
    const FILTER_SHOW_ALL = "all";

    const FILTER_NAMES = [
        self::FILTER_SHOW_ONLY_EXPIRED_AND_RUN_OUT => "Mendekati Kadaluarsa / Habis",
        self::FILTER_SHOW_ALL => "Semua",
    ];

    protected $updatesQueryString = [
        "filter" => ["except" => "all"],
    ];

    protected $listeners = [
        "stock:delete" => "deleteStock"
    ];

    public function mount(Request $request, $barangId, $inGuestMode = false)
    {
        $this->barangId = $barangId;
        $this->inGuestMode = $inGuestMode;
        $this->filter = $request->query("filter", self::FILTER_SHOW_ALL);
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
            "filter_names" => self::FILTER_NAMES,
            "barang" => $this->getBarangProperty(),
            "stocks" => $this->getStocksProperty(),
        ]);
    }
}
