<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'options' => $this->whenLoaded(
                'optionValues',
                OptionsSelectedValueResource::collection($this->optionValues)
            ),
            'status' => OrderStatus::tryFrom($this->status)?->getEnglishLabel(),
            'delivered_at' => $this->delivered_at?->toFormattedDateString(),
        ];
    }
}
