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

    public function getReasonTypes(Stock $stock)
    {
        if ($stock->bisa_dikembalikan) {
            return [
                AlasanTransaksi::PEMBUANGAN => "Pembuangan",
                AlasanTransaksi::PENGEMBALIAN => "Pengembalian",
            ];
        } else {
            return [
                AlasanTransaksi::PEMBUANGAN => "Pembuangan",
            ];
        }
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

            "reason_types" => $this->getReasonTypes($stock)
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
            "alasan" => ["required", Rule::in(array_keys($this->getReasonTypes($stock)))],
            "jumlah_dikeluarkan" => ["required", "numeric", "gte:0", "lte:{$jumlahStockAkhir}"],
        ])->validate();

        DB::beginTransaction();

        switch ($data["alasan"]) {
            case AlasanTransaksi::PENGEMBALIAN:
                $inventory->returnStock($stock, $data["jumlah_dikeluarkan"]);
                break;
            case AlasanTransaksi::PEMBUANGAN:
                $inventory->throwAwayStock($stock, $data["jumlah_dikeluarkan"]);
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
