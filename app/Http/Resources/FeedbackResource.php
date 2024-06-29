<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'datetime' => $this->datetime ? DateTime::createFromFormat('Y-m-d H:i:s', $this->datetime)->getTimestamp() : null,
            'rating' => $this->rating,
            'service' => [
                'id' => $this->service->id,
                'title' => $this->service->title
            ]
        ];
    }
}
