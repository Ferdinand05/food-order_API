<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'customer_name', 'table_no', 'order_date', 'order_time', 'status', 'total', 'waitress_id', 'cashier_id'];

    public function sumOrderPrice()
    {
        $orders = OrderDetail::whereOrder_id($this->id)->get();
        $totalPrice = 0;
        foreach ($orders as $o) {
            $subtotal = $o->price * $o->qty;
            $totalPrice += $subtotal;
        }

        return $totalPrice;
    }

    /**
     * Get all of the orderDetail for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetail(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    /**
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function waitress(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waitress_id', 'id');
    }
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id', 'id');
    }
}
