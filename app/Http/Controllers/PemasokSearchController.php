<?php

namespace App\Http\Controllers;

use App\Pemasok;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PemasokSearchController extends Controller
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
        $paginator = Pemasok::query()
            ->when($request->query("term"), function (Builder $builder, $term) {
                $builder->where("nama", "like", "%$term%");
            })
            ->orderBy("nama")
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                ->map(function (Pemasok $pemasok) {
                    return [
                        "id" => $pemasok->id,
                        "text" => $pemasok->nama,
                    ];
                }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
