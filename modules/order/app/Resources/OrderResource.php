<?php

namespace Modules\order\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'user_id'            => $this->user_id,
            'trade_type'         => $this->trade_type,
            'price'              => $this->price,
            'initial_quantity'   => $this->initial_quantity,
            'remaining_quantity' => $this->remaining_quantity,
            'order_status'       => $this->order_status,
            'created_at'         => $this->created_at,
        ];
    }
}
