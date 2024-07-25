<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route Auth > Login

Route::post('login', [AuthController::class, 'login']);


//SECTION - LOGIN
Route::middleware(['auth:sanctum'])->group(function () {

    // profile
    Route::get('me', [AuthController::class, 'me']);

    // Logout
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('user', [UserController::class, 'store'])->name('user.store')->middleware(['ableCreateUser']);
    // update username
    Route::put('user/{id}', [UserController::class, 'update'])->name('user.update');
    // Route Item
    Route::apiResource('item', ItemController::class)->middleware(['ableCreateUpdateItem']);


    // SECTION Route Order
    // Order Report
    Route::get('order/report', [OrderController::class, 'orderReport'])->name('order.report')->middleware(['ableAccessOrderReport']);
    // Order Set as done
    Route::get('order/{id}/set-as-done', [OrderController::class, 'setAsDone'])->name('order.setAsDone')->middleware(['ableFinishOrder']);
    // set as Paid
    Route::get('order/{id}/payment', [OrderController::class, 'payment'])->name('order.payment')->middleware(['ablePaidOrder']);
    Route::apiResource('order', OrderController::class);
});
// End Route Login
