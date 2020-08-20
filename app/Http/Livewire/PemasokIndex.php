<?php

namespace App\Http\Livewire;

use App\Constants\MessageState;
use App\Pemasok;
use App\Support\SessionHelper;
use Livewire\Component;
use Livewire\WithPagination;

class PemasokIndex extends Component
{
    use WithPagination;

    protected $listeners = [
        "pemasok:delete" => "deletePemasok"
    ];

    public function deletePemasok($pemasokId)
    {
        try {
            Pemasok::query()
                ->where("id", $pemasokId)
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
        return view('livewire.pemasok-index', [
            "pemasoks" => Pemasok::query()
                ->orderBy("nama")
                ->paginate()
        ]);
    }
}
