<?php

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    $order = OrderDetail::whereOrder_id(49)->get();
    $totalPrice = 0;
    foreach ($order as $o) {
        $subtotal = $o->price * $o->qty;
        $totalPrice += $subtotal;
    }
    return $totalPrice;
});
