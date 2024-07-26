<?php

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Item;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    Benchmark::dd([
        'Scenario Item' => fn () => Cache::remember('item', 60, fn () => Item::all()),
        'Scenario Order' => fn () => Cache::remember('order', 60, fn () => Order::with(['orderDetail', 'waitress', 'cashier'])->get()),
        'Scenario Order Detail' => fn () => Cache::remember('order-detail', 60, fn () => OrderDetail::with('item')->get()),
    ]);

    // Benchmark::dd([
    //     'Scenario Item' => fn () => Item::all(),
    //     'Scenario Order' => fn () => Order::with(['orderDetail', 'waitress', 'cashier'])->get(),
    //     'Scenario Order Detail' => fn () => OrderDetail::with('item')->get(),
    // ]);
});
