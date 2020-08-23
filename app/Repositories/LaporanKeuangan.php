<?php


namespace App\Repositories;


use App\ItemPenjualan;
use App\TransaksiKeuangan;
use App\TransaksiStock;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class LaporanKeuangan
{
    public const FILTER_TYPES = [
        LaporanKeuangan::FILTER_TYPE_DAY => "Hari",
        LaporanKeuangan::FILTER_TYPE_MONTH => "Bulan",
        LaporanKeuangan::FILTER_TYPE_YEAR => "Tahun",
    ];
    public const FILTER_INPUT_TYPES = [
        LaporanKeuangan::FILTER_TYPE_DAY => "date",
        LaporanKeuangan::FILTER_TYPE_MONTH => "month",
        LaporanKeuangan::FILTER_TYPE_YEAR => "number",
    ];
    public const FILTER_TYPE_DAY = 1;
    public const FILTER_TYPE_MONTH = 2;
    public const FILTER_TYPE_YEAR = 3;

    public function getFilterDefaultValue($filterType)
    {
        switch ($filterType) {
            case LaporanKeuangan::FILTER_TYPE_DAY:
                return today()->format("Y-m-d");
            case LaporanKeuangan::FILTER_TYPE_MONTH:
                return today()->format("Y-m");
            case LaporanKeuangan::FILTER_TYPE_YEAR:
                return today()->format("Y");
            default:
                throw new Exception("Error: Unknown filter type");
        }
    }

    /**
     * @param $filterType
     * @param $filterValue
     * @return \Illuminate\Database\Concerns\BuildsQueries|Builder|mixed
     */
    public function getQuery($filterType, $filterValue)
    {
        return TransaksiKeuangan::query()
            ->select("*")
            ->when($filterType, function (Builder $builder) use ($filterType, $filterValue) {
                switch ($filterType) {
                    case self::FILTER_TYPE_DAY:
                        $builder
                            ->whereDate("tanggal_transaksi", $filterValue)
                            ->addSelect([
                                DB::raw(/** @lang MariaDB */ "(
                                    SUM(jumlah) OVER (ORDER BY tanggal_transaksi, id))
                                        + (SELECT COALESCE (SUM(jumlah), 0) FROM transaksi_keuangan WHERE DATE(tanggal_transaksi) < '$filterValue')
                                        + COALESCE((SELECT jumlah FROM saldo_awal LIMIT 1), 0)
                                        AS saldo"
                                ),
                            ]);
                        break;
                    case self::FILTER_TYPE_MONTH:
                        $year = Date::make($filterValue)->format("Y");
                        $month = Date::make($filterValue)->format("m");
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
                            ->whereYear("tanggal_transaksi", $filterValue)
                            ->addSelect([
                                DB::raw(/** @lang MariaDB */ "
                                        + (SUM(jumlah) OVER (ORDER BY tanggal_transaksi, id))
                                        + (SELECT COALESCE(SUM(jumlah), 0) FROM transaksi_keuangan WHERE YEAR(tanggal_transaksi) < '$filterValue')
                                        + COALESCE((SELECT jumlah FROM saldo_awal LIMIT 1), 0)
                                        AS saldo
                                    "),
                            ]);
                        break;
                    default:
                        break;
                }
            })
            ->orderBy("tanggal_transaksi")
            ->orderBy("id")
            ->with([
                "entitas_terkait" => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        TransaksiStock::class => "stock.barang",
                        ItemPenjualan::class => "barang",
                    ]);
                }
            ]);
    }
}