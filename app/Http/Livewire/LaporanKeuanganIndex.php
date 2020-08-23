<?php

namespace App\Http\Livewire;

use App\Repositories\LaporanKeuangan;
use App\SaldoAwal;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanKeuanganIndex extends Component
{
    use WithPagination;

    public $filterType;
    public $filterValue;

    protected $updatesQueryString = [
        "filterType" => ["except" => ""],
        "filterValue" => ["except" => ""],
    ];

    public function mount(Request $request, LaporanKeuangan $laporanKeuangan)
    {
        $this->filterType = $request->query("filterType", LaporanKeuangan::FILTER_TYPE_DAY);
        $this->filterValue = $request->query("filterValue", $laporanKeuangan->getFilterDefaultValue($this->filterType));
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->filterValue = app(LaporanKeuangan::class)->getFilterDefaultValue($this->filterType);
    }

    public function updatingFilterValue()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.laporan-keuangan-index', [
            "saldo_awal" => SaldoAwal::query()->first(),
            "filterTypes" => LaporanKeuangan::FILTER_TYPES,
            "filterInputType" => LaporanKeuangan::FILTER_INPUT_TYPES[$this->filterType],
            "transaksis" => app(LaporanKeuangan::class)->getQuery($this->filterType, $this->filterValue)->paginate()
        ]);
    }
}
