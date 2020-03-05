<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // We need to verify that the price hasn't been tampered with
        // To do that we need to calculate the price here the same way we do in the frontend
        return [
            'nickname' => 'bail|required|alphanumeric|min:3|max:100',
            'in_game_server' => 'bail|required|exists:servers,id',
            'comments' => 'nullable|string|min:5|max:200',
            'price' => 'bail|required|decimal',
            'appear_offline' => 'bail|required|boolean',
            'spell_order' => 'bail|required|in:d,f',
            // Not any user, must be a booster
            'booster_id' => 'nullable|exists:users,id',
            'service_service_type_id' => 'bail|required|exists:service_service_type,id',
            // Automatically generated
            // 'purchase' => "Nothing",
        ];
    }
}
