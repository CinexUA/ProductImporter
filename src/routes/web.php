<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductImporterController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

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

Route::group(['middleware' => ['auth']], function ($r) {

    $r->get('/home', [HomeController::class, 'index'])->name('home');
    $r->get('/reset-all', [HomeController::class, 'reset'])->name('reset');

    $r->group(['prefix' => 'product-importer', 'as' => 'product-importer.'], function ($r){
        $r->get('', [ProductImporterController::class, 'index'])->name('index');
        $r->post('', [ProductImporterController::class, 'store'])->middleware('max_execution:10')->name('store');
        $r->get('history', [ProductImporterController::class, 'history'])->name('history');
        $r->get('history/{import}/log', [ProductImporterController::class, 'historyLog'])->name('history.log');
        $r->delete('history/{history}', [ProductImporterController::class, 'removeHistory'])->name('history.remove');
    });

});

