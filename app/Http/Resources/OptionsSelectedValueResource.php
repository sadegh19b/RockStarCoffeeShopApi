<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionsSelectedValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $option = $this->firstOption();

        return [
            'id' => $option->id,
            'name' => $option->name,
            'value' => [
                'id' => $this->id,
                'name' => $this->name,
            ]
        ];
    }
}
