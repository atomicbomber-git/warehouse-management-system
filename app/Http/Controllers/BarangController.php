<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Constants\MessageState;
use App\Providers\AuthServiceProvider;
use App\Support\SessionHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_BARANG);
        return $this->responseFactory->view("barang.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_BARANG);
        return $this->responseFactory->view("barang.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_BARANG);

        $data = $request->validate([
            "nama" => ["required", "string", Rule::unique(Barang::class)],
            "satuan" => ["required", "string"],
            "harga_jual" => ["required", "numeric", "gte:0"],
        ]);

        Barang::query()->create($data);

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("barang.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Barang $barang)
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_BARANG);

        return $this->responseFactory->view("barang.edit", [
            "barang" => $barang,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Barang $barang
     * @return RedirectResponse
     */
    public function update(Request $request, Barang $barang)
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_BARANG);

        $data = $request->validate([
            "nama" => ["required", "string", Rule::unique(Barang::class)->ignoreModel($barang)],
            "satuan" => ["required", "string"],
            "harga_jual" => ["required", "numeric", "gte:0"],
            "disembunyikan" => ["nullable", Rule::in(["on"])]
        ]);

        $data["disembunyikan"] = isset($data["disembunyikan"]);
        $barang->update($data);

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("barang.edit", $barang);
    }
}
