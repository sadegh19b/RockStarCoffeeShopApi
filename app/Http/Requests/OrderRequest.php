<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $product = Product::with('options.values')->find($this->product_id);

        $rules = [
            'product_id' => ['required', 'numeric', 'exists:products,id'],
        ];

        foreach ($product?->options ?? [] as $option) {
            $optionValuesIds = $option->values->pluck('id')->toArray();

            $rules["option_{$option->id}_option_value_id"] = [
                'required',
                'numeric',
                Rule::in($optionValuesIds),
            ];
        }

        return $rules;
    }

    public function getOptionValuesId(): array
    {
        $optionKeys = array_flip(preg_grep('/^option_\d+_option_value_id$/', array_keys($this->validated())));
        $optionValues = array_intersect_key($this->validated(), $optionKeys);

        return array_values($optionValues);
    }
}
