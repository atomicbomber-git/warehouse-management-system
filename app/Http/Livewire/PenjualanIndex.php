<?php

namespace App\Http\Livewire;

use App\Constants\MessageState;
use App\Penjualan;
use App\Support\SessionHelper;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanIndex extends Component
{
    use WithPagination;

    protected $listeners = [
        "penjualan:delete" => "deletePenjualan",
    ];

    public function deletePenjualan($penjualanId)
    {
        try {
            /** @var Penjualan $penjualan */
            $penjualan = Penjualan::query()
                ->findOrFail($penjualanId);

            $penjualan->items()->delete();
            $penjualan->delete();

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

    public function render()
    {
        return view('livewire.penjualan-index', [
            "penjualans" => Penjualan::query()
                ->orderByDesc("tanggal_penjualan")
                ->paginate()
        ]);
    }
}
