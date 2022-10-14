<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->when(
                $request->user()->isAdmin(),
                $this->whenLoaded('user', $this->user->name)
            ),
            'email' => $this->when(
                $request->user()->isAdmin(),
                $this->whenLoaded('user', $this->user->email)
            ),
            'phone' => $this->when(
                $request->user()->isAdmin(),
                $this->whenLoaded('user', $this->user->phone)
            ),
            'status' => $this->status,
            'comment' => $this->comment,
            'address' => $this->address,
            'total_cost' => $this->total_cost,
            'items_count' => $this->whenCounted('orderItems'),
            'created_at' => $this->created_at->format('H:i d.m.Y'),
            'updated_at' => $this->updated_at->format('H:i d.m.Y'),
        ];
    }
}
