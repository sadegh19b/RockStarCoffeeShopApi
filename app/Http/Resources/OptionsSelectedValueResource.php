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
        $option = $this->options()->wherePivot('option_value_id', $this->pivot->option_value_id)->first();

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
