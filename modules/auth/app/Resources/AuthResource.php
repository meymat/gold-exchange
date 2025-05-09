<?php

namespace Modules\auth\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\user\app\Resources\UserResource;

class AuthResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user'  => new UserResource($this->resource['user']),
            'token' => $this->resource['token'],
        ];
    }
}
