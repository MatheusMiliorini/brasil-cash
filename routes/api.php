<?php

use App\Http\Controllers\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/transactions')->group(function () {

    Route::post('/', [TransactionsController::class, 'save']);

    Route::post('/{transaction}/capture', [TransactionsController::class, 'capture']);
});
