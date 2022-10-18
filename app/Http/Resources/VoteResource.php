<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
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
            'up' => $this->whenLoaded('question', $this->question->votes_up),
            'down' => $this->whenLoaded('question', $this->question->votes_down),
            'status' => $this->status,
            'created_at' => $this->created_at->format('H:i d.m.Y'),
            'updated_at' => $this->updated_at->format('H:i d.m.Y'),
        ];
    }
}
