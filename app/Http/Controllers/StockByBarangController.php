<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Constants\MessageState;
use App\Pemasok;
use App\Repositories\Inventory;
use App\Stock;
use App\Support\SessionHelper;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockByBarangController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($barang)
    {
        return $this->responseFactory->view("stock-by-barang.index", [
            "barang_id" => $barang
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Barang $barang)
    {
        return $this->responseFactory->view("stock-by-barang.create", [
            "barang" => $barang,
            "old_pemasok" => Pemasok::query()->find(old("pemasok_id")),
            "old_barang" => Barang::query()->find(old("barang_id")),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Barang $barang, Inventory $inventory)
    {
        $data = $request->validate([
            "pemasok_id" => ["required", Rule::exists(Pemasok::class, "id")],
            "tanggal_masuk" => ["required", "date"],
            "tanggal_kadaluarsa" => ["required", "date"],
            "jumlah" => ["required", "numeric", "gte:1"],
            "harga_satuan" => ["required", "numeric", "gte:0"],
            "bisa_dikembalikan" => ["required", "boolean"],
        ]);

        $inventory->purchaseBarang($barang, $data);

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory
            ->redirectToRoute("stock-grouped-by-barang.stock-by-barang.index", $barang);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        //
    }
}
