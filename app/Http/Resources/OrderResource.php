<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'waitress' => [
                'id' => $this->waitress->id,
                'name' => $this->waitress->name
            ],
            'cashier' => [
                'id' => $this->cashier?->id,
                'name' => $this->cashier?->name
            ],
        ];
    }
}
