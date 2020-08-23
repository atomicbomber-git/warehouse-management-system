<?php

namespace App\Http\Livewire;

use App\ItemPenjualan;
use App\SaldoAwal;
use App\TransaksiKeuangan;
use App\TransaksiStock;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
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

    protected $updatesQueryString = [
        "filterType" => ["except" => ""],
        "filterValue" => ["except" => ""],
    ];

    public function mount(Request $request)
    {
        $this->filterType = $request->query("filterType", self::FILTER_TYPE_DAY);
        $this->filterValue = $request->query("filterValue", $this->getFilterDefaultValue($this->filterType));
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
                throw new Exception("Error: Unknown filter type");
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

    public function updatingFilterValue()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.laporan-keuangan-index', [
            "saldo_awal" => SaldoAwal::query()->first(),
            "filterTypes" => self::FILTER_TYPES,
            "filterInputType" => self::FILTER_INPUT_TYPES[$this->filterType],
            "transaksis" => TransaksiKeuangan::query()
                ->select("*")
                ->when($this->filterType, function (Builder $builder, $filterType) {
                    switch ($filterType) {
                        case self::FILTER_TYPE_DAY:
                            $builder
                                ->whereDate("tanggal_transaksi", $this->filterValue)
                                ->addSelect([
                                    "saldo_awal" => TransaksiKeuangan::query()
                                        ->whereDate("tanggal_transaksi", "<", $this->filterValue)
                                        ->selectRaw("SUM(jumlah)"),

                                    DB::raw(/** @lang MariaDB */ <<<QUERY
    + (SUM(jumlah) OVER (ORDER BY tanggal_transaksi, id))
    + (SELECT COALESCE (SUM(jumlah), 0) FROM transaksi_keuangan WHERE DATE(tanggal_transaksi) < '$this->filterValue')
    + COALESCE((SELECT jumlah FROM saldo_awal LIMIT 1), 0)
    AS saldo
QUERY
                                    ),
                                ]);
                            break;
                        case self::FILTER_TYPE_MONTH:
                            $year = Date::make($this->filterValue)->format("Y");
                            $month = Date::make($this->filterValue)->format("m");

                            $builder
                                ->whereMonth("tanggal_transaksi", $month)
                                ->whereYear("tanggal_transaksi", $year)
                                ->addSelect([
                                    DB::raw(/** @lang MariaDB */ "
                                        + (SUM(jumlah) OVER (ORDER BY tanggal_transaksi, id))
                                        + (
                                            SELECT
                                                COALESCE(SUM(jumlah), 0) FROM transaksi_keuangan 
                                                    WHERE
                                                          (YEAR(tanggal_transaksi) = '$year' AND MONTH(tanggal_transaksi) < '$month') OR
                                                          (YEAR(tanggal_transaksi) < '$year')
                                        )
                                        + COALESCE((SELECT jumlah FROM saldo_awal LIMIT 1), 0)
                                        AS saldo
                                    "),
                                ]);
                            break;
                        case self::FILTER_TYPE_YEAR:
                            $builder
                                ->whereYear("tanggal_transaksi", $this->filterValue)
                                ->addSelect([
                                    DB::raw(/** @lang MariaDB */ "
                                        + (SUM(jumlah) OVER (ORDER BY tanggal_transaksi, id))
                                        + (SELECT COALESCE(SUM(jumlah), 0) FROM transaksi_keuangan WHERE YEAR(tanggal_transaksi) < '$this->filterValue')
                                        + COALESCE((SELECT jumlah FROM saldo_awal LIMIT 1), 0)
                                      
                                        AS saldo
                                    "),
                                ]);
                            break;
                        default:
                            break;
                    }
                })
                ->orderByDesc("tanggal_transaksi")
                ->orderByDesc("id")
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
