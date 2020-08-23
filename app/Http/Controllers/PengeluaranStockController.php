<?php

namespace App\Http\Controllers;

use App\Constants\AlasanTransaksi;
use App\Constants\MessageState;
use App\Repositories\Inventory;
use App\Stock;
use App\Support\SessionHelper;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PengeluaranStockController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Stock $stock)
    {
        return $this->responseFactory->view("pengeluaran-stock.create", [
            "stock" => Stock::query()
                ->whereKey($stock->getKey())
                ->withJumlah()
                ->firstOrFail(),

            "reason_types" => [
                AlasanTransaksi::PEMBUANGAN => "Pembuangan",
                AlasanTransaksi::PENGEMBALIAN => "Pengembalian",
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Stock $stock, ValidatorFactory $validatorFactory, Inventory $inventory)
    {
        $jumlahStockAkhir = Stock::query()
            ->whereKey($stock->getKey())
            ->withJumlah()
            ->value("jumlah") ?? 0;

        $data = $validatorFactory->make($request->all(), [
            "alasan" => ["required", Rule::in([
                AlasanTransaksi::PERBAIKAN,
                AlasanTransaksi::PEMBUANGAN,
                AlasanTransaksi::PENGEMBALIAN,
            ])],

            "jumlah_dikeluarkan" => ["required", "numeric", "gte:0"],
        ])->sometimes("jumlah_dikeluarkan", ["lte:{$jumlahStockAkhir}"], function ($attributes) {
            return in_array($attributes->alasan, [
                AlasanTransaksi::PEMBUANGAN,
                AlasanTransaksi::PENGEMBALIAN,
            ]);
        })->validate();

        DB::beginTransaction();

        $inventory->returnStock($stock, $data["jumlah_dikeluarkan"]);

        switch ($data["alasan"]) {
            case AlasanTransaksi::PENGEMBALIAN:
                $inventory->returnStock($stock);
                break;
            case AlasanTransaksi::PEMBUANGAN:
                $inventory->throwAwayStock($stock);
                break;
        }

        DB::commit();

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        $jumlahStockAkhir = Stock::query()
            ->whereKey($stock->getKey())
            ->withJumlah()
            ->value("jumlah") ?? 0;

        return ($jumlahStockAkhir > 0) ?
            $this->responseFactory->redirectToRoute("stock-by-barang.pengeluaran.create", $stock) :
            $this->responseFactory->redirectToRoute("stock-grouped-by-barang.stock-by-barang.index", $stock->barang_id);
    }
}
