<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangSearchController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PemasokSearchController;
use App\Http\Controllers\PengeluaranStockController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PrintLaporanKeuanganController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\StockByBarangController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockGroupedByBarangController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    "register" => false,
    "reset" => false,
    "confirm" => false,
    "verify" => false,
]);

Route::get('/', function () {
    return redirect()->route("login");
});

Route::resource("pemasok-search", class_basename(PemasokSearchController::class))
    ->only(["index"]);

Route::resource("barang-search", class_basename(BarangSearchController::class))
    ->only(["index"]);

Route::resource("user", class_basename(UserController::class))
    ->except(["show", "destroy"]);

Route::resource("barang", class_basename(BarangController::class))
    ->except(["show", "destroy"]);

Route::resource("pemasok", class_basename(PemasokController::class))
    ->except(["show", "destroy"]);

Route::resource("penjualan", class_basename(PenjualanController::class))
    ->except(["show", "destroy"]);

Route::resource("stock-grouped-by-barang", class_basename(StockGroupedByBarangController::class))
    ->parameter("stock-grouped-by-barang", "barang")
    ->only(["index"]);

Route::resource("stock-grouped-by-barang.stock-by-barang", class_basename(StockByBarangController::class))
    ->parameters([
        "stock-grouped-by-barang" => "barang",
        "stock-by-barang" => "stock",
    ])
    ->except(["show", "edit", "update", "destroy"])
    ->shallow();

Route::resource("stock-by-barang.pengeluaran", class_basename(PengeluaranStockController::class))
    ->parameter("stock-by-barang", "stock")
    ->only(["create", "store"]);

Route::resource("laporan-keuangan", class_basename(LaporanKeuanganController::class))
    ->only(["index"]);

Route::resource("print-laporan-keuangan", class_basename(PrintLaporanKeuanganController::class))
    ->only(["index"]);

Route::resource("saldo-awal", class_basename(SaldoAwalController::class))
    ->only(["edit", "update"]);