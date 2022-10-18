<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'in_stock' => $this->in_stock,
            'rating' => $this->rating,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'questions_count' => $this->whenCounted('questions'),
            'reviews_count' => $this->whenCounted('reviews'),
            'created_at' => $this->created_at->format('H:i d.m.Y'),
            'updated_at' =>  $this->updated_at->format('H:i d.m.Y'),
            'images' => ImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
