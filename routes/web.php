<?php

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Item;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

Route::get('/', function () {


    // Benchmark::dd([
    //     'Scenario Item' => fn () => Cache::remember('item', 60, fn () => Item::all()),
    //     'Scenario Order' => fn () => Cache::remember('order', 60, fn () => Order::with(['orderDetail', 'waitress', 'cashier'])->get()),
    //     'Scenario Order Detail' => fn () => Cache::remember('order-detail', 60, fn () => OrderDetail::with('item')->get()),
    // ], 3);


    Benchmark::dd([
        'Scenario Item' => fn () => Redis::get('item'),
        'Scenario Order' => fn () => Redis::get('order'),
        'Scenario Order Detail' => fn () => Redis::get('order-detail'),
    ], 3);
});


Route::get('putCache', function () {

    $order = Order::with(['orderDetail', 'waitress', 'cashier'])->get();
    $item = Item::all();
    $orderDetail = OrderDetail::with('item')->get();

    Redis::set('order', $order);
    Redis::set('item', $item);
    Redis::set('order-detail', $orderDetail);
});
