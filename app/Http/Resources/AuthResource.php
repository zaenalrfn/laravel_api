<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'status'    => $this['status'] ?? true,
            'message'   => $this['message'] ?? 'Operation successful',
            'data'      => [
                'user' => $this['user'],
                'token' => $this['token'] ?? null,
                'token_type' => $this['token_type'] ?? 'Bearer',
            ],
        ];
    }
}
