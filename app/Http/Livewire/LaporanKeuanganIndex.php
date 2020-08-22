<?php

namespace App\Http\Livewire;

use App\ItemPenjualan;
use App\TransaksiKeuangan;
use App\TransaksiStock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanKeuanganIndex extends Component
{
    use WithPagination;

    const FILTER_TYPE_DAY = 1;
    const FILTER_TYPE_MONTH = 2;
    const FILTER_TYPE_YEAR = 3;

    const FILTER_TYPES = [
        self::FILTER_TYPE_DAY => "Hari",
        self::FILTER_TYPE_MONTH => "Bulan",
        self::FILTER_TYPE_YEAR => "Tahun",
    ];

    const FILTER_INPUT_TYPES = [
        self::FILTER_TYPE_DAY => "date",
        self::FILTER_TYPE_MONTH => "month",
        self::FILTER_TYPE_YEAR => "number",
    ];

    public $filterType;
    public $filterValue;

    public function mount()
    {
        $this->filterType = self::FILTER_TYPE_DAY;
        $this->filterValue = $this->getFilterDefaultValue($this->filterType);
    }

    public function getFilterDefaultValue($filterType)
    {
        switch ($filterType) {
            case self::FILTER_TYPE_DAY:
                return today()->format("Y-m-d");
            case self::FILTER_TYPE_MONTH:
                return today()->format("Y-m");
            case self::FILTER_TYPE_YEAR:
                return today()->format("Y");
            default:
                throw new \Exception("Error: Unknown filter type");
        }
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->filterValue = $this->getFilterDefaultValue($this->filterType);
    }

    public function render()
    {
        return view('livewire.laporan-keuangan-index', [
            "filterTypes" => self::FILTER_TYPES,
            "filterInputType" => self::FILTER_INPUT_TYPES[$this->filterType],
            "transaksis" => TransaksiKeuangan::query()
                ->when($this->filterType, function (Builder $builder, $filterType) {
                    switch ($filterType) {
                        case self::FILTER_TYPE_DAY:
                            $builder->whereDate("tanggal_transaksi", $this->filterValue);
                            break;
                        case self::FILTER_TYPE_MONTH:
                            $builder
                                ->whereMonth("tanggal_transaksi", Date::make($this->filterValue)->format("m"))
                                ->whereYear("tanggal_transaksi", Date::make($this->filterValue)->format("Y"));
                            break;
                        case self::FILTER_TYPE_YEAR:
                            $builder->whereYear("tanggal_transaksi", $this->filterValue);
                            break;
                        default:
                            break;
                    }
                })
                ->latest("tanggal_transaksi")
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
