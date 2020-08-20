<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\PemasokController;
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
    return view('welcome');
});

Route::resource("user", class_basename(UserController::class))
    ->except(["show", "destroy"]);

Route::resource("barang", class_basename(BarangController::class))
    ->except(["show", "destroy"]);

Route::resource("pemasok", class_basename(PemasokController::class))
    ->except(["show", "destroy"]);


Route::get('/home', 'HomeController@index')->name('home');
