<?php

namespace App\Http\Controllers;

use App\Providers\AuthServiceProvider;
use App\Repositories\LaporanKeuangan;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class PrintLaporanKeuanganController extends Controller
{
    const PAGE_SIZE = 30;
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
    public function index(Request $request, LaporanKeuangan $laporanKeuangan)
    {
        $this->authorize(AuthServiceProvider::VIEW_LAPORAN_KEUANGAN);

        $filterType = $request->query("filterType", LaporanKeuangan::FILTER_TYPE_DAY);
        $filterValue = $request->query("filterValue", $laporanKeuangan->getFilterDefaultValue($filterType));

        return $this->responseFactory->view("print-laporan-keuangan.index", [
            "filterType" => $filterType,
            "filterValue" => $filterValue,
            "laporan_pages" => $laporanKeuangan->getQuery(
                $filterType,
                $filterValue,
            )->get()->chunk(self::PAGE_SIZE)
        ]);
    }
}
