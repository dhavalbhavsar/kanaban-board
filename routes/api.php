<?php

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

Route::resource('columns', \App\Http\Controllers\API\ColumnController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

Route::resource('cards', \App\Http\Controllers\API\CardController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

Route::post('cards/order', [\App\Http\Controllers\API\CardController::class, 'order'])->name('cards.order');
