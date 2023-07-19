<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStatusUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                //Rule::in(OrderStatus::getValues([OrderStatus::WAITING->value]))
                Rule::in(OrderStatus::getValues())
            ],
        ];
    }

    public function messages()
    {
        return [
            'status.in' => 'The selected status is invalid. valid statuses: waiting, preparation, ready, delivered.',
        ];
    }
}
