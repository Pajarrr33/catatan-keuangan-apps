<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(TransactionController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/transactions', 'store');
    Route::put('/transactions/{id}', 'update');
    Route::delete('/transactions/{id}', 'destroy');
});
