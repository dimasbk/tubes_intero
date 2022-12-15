<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportAPIController;
use App\Http\Controllers\PortofolioBeliAPIController;
use App\Http\Controllers\PortofolioJualAPIController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/report/{user_id}/{tahun}', [ReportAPIController::class, 'getReport']);

Route::get('/portofoliobeli', [PortofolioBeliAPIController::class, 'allData']);
Route::get('/portofoliobeli/{user_id}', [PortofolioBeliAPIController::class, 'getData']);
Route::post('/portofoliobeli/addbeli', [PortofolioBeliAPIController::class, 'insertData']);
Route::post('/portofoliobeli/editbeli', [PortofolioBeliAPIController::class, 'editData']);
Route::get('/portofoliobeli/delete/{id_portofolio_beli}', [PortofolioBeliAPIController::class, 'deleteData']);

Route::get('/portofoliojual', [PortofolioJualAPIController::class, 'index']);
Route::get('/portofoliojual/{user_id}', [PortofolioJualAPIController::class, 'getdata']);
Route::post('/portofoliojual/addjual', [PortofolioJualAPIController::class, 'insertData']);
Route::post('/portofoliojual/editjual', [PortofolioJualAPIController::class, 'editData']);
Route::get('/portofoliojual/delete/{id_portofolio_jual}', [PortofolioJualAPIController::class, 'deleteData']);