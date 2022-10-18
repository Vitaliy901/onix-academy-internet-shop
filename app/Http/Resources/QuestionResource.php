<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'votes_up' => $this->votes_up,
            'votes_down' => $this->votes_down,
            'answers_quantity' => $this->whenCounted('answers'),
            'created_at' => $this->created_at->format('H:i d.m.Y'),
            'updated_at' => $this->updated_at->format('H:i d.m.Y'),
        ];
    }
}
