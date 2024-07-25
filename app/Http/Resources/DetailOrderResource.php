<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'table_no' => $this->table_no,
            'order_date' => $this->order_date,
            'order_time' => $this->order_time,
            'total' => $this->total,
            'status' => $this->status,
            'waitress' => $this->waitress,
            'cashier' => $this->cashier,
            'order_detail' => $this->orderDetail
        ];
    }
}
