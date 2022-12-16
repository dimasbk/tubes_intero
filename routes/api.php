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

Route::get('/portofolio/report', [ReportAPIController::class, 'getReport']);

Route::get('/portofolio/jual', [PortofolioJualAPIController::class, 'getdata']);
Route::post('/portofolio/jual/addjual', [PortofolioJualAPIController::class, 'insertData']);
Route::post('/portofolio/jual/editjual', [PortofolioJualAPIController::class, 'editData']);
Route::get('/portofolio/jual/delete', [PortofolioJualAPIController::class, 'deleteData']);