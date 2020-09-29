<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Pemasok;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BarangSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {

        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $paginator = Barang::query()
            ->when($request->query("term"), function (Builder $builder, $term) {
                $builder->where("nama", "like", "%$term%");
            })
            ->where("disembunyikan", 0)
            ->orderBy("nama")
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                    ->map(function (Barang $barang) {
                        return [
                            "id" => $barang->id,
                            "text" => $barang->nama,
                        ];
                    }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
