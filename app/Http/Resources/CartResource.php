<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'name' =>  $this->whenLoaded('product', $this->product->name),
            'image' => $this->whenLoaded('product', $this->product->images
                ->pluck('filename')
                ->firstOrFail()),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at->format('H:i d.m.Y'),
        ];
    }
}
