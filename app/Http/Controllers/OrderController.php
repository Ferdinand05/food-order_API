<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetailItemResource;
use App\Http\Resources\DetailOrderResource;
use App\Http\Resources\OrderResource;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class OrderController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware(): array
    {
        return [
            // 'auth:sanctum',
            new Middleware('ableCreateOrder', ['store'])
        ];
    }

    public function index()
    {
        $orders = Order::latest()->get();

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'max:100'],
            'table_no' => ['required', 'max:5'],

        ]);


        DB::beginTransaction();

        try {
            $data = $request->only(['customer_name', 'table_no']);
            $data['order_date'] = Carbon::now('Asia/Jakarta')->toDateString();
            $data['order_time'] = Carbon::now('Asia/Jakarta')->format('H:i:s');
            $data['status'] = 'Ordered';
            $data['total'] = 0;
            $data['waitress_id'] = Auth::id();
            $data['items'] = $request->items;

            // insert data
            $order = Order::create($data);



            // menambahkan order Detail
            collect($data['items'])->each(function ($item) use ($order) {

                $dataItem = Item::find($item['id']);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'price' => $dataItem->price,
                    'qty' => $item['quantity']
                ]);
            });


            // update harga, total dari Order Detail
            $order->update([
                'total' => $order->sumOrderPrice()
            ]);


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }

        return new DetailOrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('orderDetail:id,order_id,item_id,price,qty', 'orderDetail.item:id,name,price', 'waitress:id,name,email,role_id', 'cashier:id,name,email,role_id')->find($id);
        return new DetailOrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function setAsDone(String $id)
    {
        $order = Order::with('orderDetail:id,order_id,item_id,price', 'waitress:id,name,email,role_id')->findOrFail($id);

        if ($order->status != 'Ordered') {
            return response()->json(['error' => 'Order cannot set to Done because the status not Ordered!']);
        }

        $order->update([
            'status' => 'Done'
        ]);

        return new DetailOrderResource($order);
    }

    public function payment($id)
    {
        $order = Order::with('orderDetail:id,order_id,item_id,price', 'waitress:id,name,email,role_id')->findOrFail($id);

        if ($order->status != 'Done') {
            return response()->json(['error' => 'Payment cannot set to Paid because the status not Done!']);
        }

        $order->update([
            'status' => 'Paid',
            'cashier_id' => Auth::id()
        ]);

        return new DetailOrderResource($order);
    }


    public function orderReport(Request $request)
    {
        $orders = Order::with(['waitress:id,name', 'cashier:id,name'])->whereMonth('order_date', $request->month)->latest()->get();

        $orderCount = $orders->count();
        $maxPayment = $orders->max('total');
        $minPayment = $orders->min('total');
        $totalPayment = $orders->sum('total');
        $data = [
            'orderCount' => $orderCount,
            'maxPayment' => $maxPayment,
            'minPayment' => $minPayment,
            'totalPayment' => $totalPayment,
            'orders' => $orders,
        ];

        return response()->json($data);
    }
}
