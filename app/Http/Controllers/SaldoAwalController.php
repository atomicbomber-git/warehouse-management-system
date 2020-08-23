<?php

namespace App\Http\Controllers;

use App\Constants\MessageState;
use App\SaldoAwal;
use App\Support\SessionHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class SaldoAwalController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SaldoAwal $saldo_awal)
    {
        return $this->responseFactory->view("saldo-awal.edit", [
            "saldo_awal" => $saldo_awal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, SaldoAwal $saldo_awal)
    {
        $data = $request->validate([
            "jumlah" => ["required", "numeric", "gte:0"]
        ]);

        $saldo_awal->update($data);

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory
            ->redirectToRoute("saldo-awal.edit", $saldo_awal);
    }
}
