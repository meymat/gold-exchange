<?php

namespace Modules\order\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'symbol'             => $this->symbol,
            'type'               => $this->type,
            'price'              => $this->price,
            'initial_quantity'   => $this->initial_quantity,
            'remaining_quantity' => $this->remaining_quantity,
            'status'             => $this->status->name,
            'expires_at'         => $this->expires_at,
            'created_at'         => $this->created_at,
        ];
    }
}
