<?php

namespace App\Http\Controllers;

use App\Penjualan;
use App\Providers\AuthServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
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
    public function index()
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_PENJUALAN);
        return $this->responseFactory->view("penjualan.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_PENJUALAN);
        return $this->responseFactory->view("penjualan.create");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show(Penjualan $penjualan)
    {
        return $this->responseFactory->view("penjualan.show", [
            "penjualan" => $penjualan
                ->load([
                    "items" => function (HasMany $hasMany) {
                        $hasMany
                            ->select("*")
                            ->selectRaw("harga_satuan * jumlah AS subtotal");
                    },
                    "items.barang"
                ]),
            "total_harga_penjualan" => $penjualan->items()->sum(DB::raw("harga_satuan * jumlah"))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }
}
