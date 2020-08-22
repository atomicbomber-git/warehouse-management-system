<?php

namespace App\Http\Controllers;

use App\LaporanKeuangan;
use App\Providers\AuthServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
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
        $this->authorize(AuthServiceProvider::VIEW_LAPORAN_KEUANGAN);
        return $this->responseFactory->view("laporan-keuangan.index");
    }

}
