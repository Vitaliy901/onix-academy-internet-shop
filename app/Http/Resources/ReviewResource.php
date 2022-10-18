<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'name' => $this->whenLoaded('user', $this->user->name),
            'text' => $this->text,
            'benefits' => $this->whenNotNull($this->benefits),
            'disadvantages' => $this->whenNotNull($this->disadvantages),
            'rating' => $this->rating,
            'bought' => $this->bought,
            'answers_quantity' => $this->whenCounted('answers'),
            'created_at' => $this->created_at->format('H:i d.m.Y'),
            'updated_at' => $this->updated_at->format('H:i d.m.Y'),
        ];
    }
}
