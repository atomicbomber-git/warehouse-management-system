<?php

namespace App\Http\Livewire;

use App\Barang;
use App\Constants\MessageState;
use App\Support\SessionHelper;
use Livewire\Component;
use Livewire\WithPagination;

class BarangIndex extends Component
{
    use WithPagination;

    protected $listeners = [
        "barang:delete" => "deleteBarang"
    ];

    public function deleteBarang($barangId)
    {
        try {
            Barang::query()
                ->where("id", $barangId)
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

    public function render()
    {
        return view('livewire.barang-index', [
            "barangs" => Barang::query()
                ->orderBy("nama")
                ->paginate()
        ]);
    }
}
