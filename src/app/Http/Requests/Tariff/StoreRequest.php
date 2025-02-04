<?php

namespace App\Http\Requests\Tariff;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ration_name' => [
                'required',
                'string',
                'max:255',
            ],
            'cooking_day_before' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'ration_name.required' => 'Название рациона обязательно для заполнения',
            'ration_name.string' => 'Поле должно быть строкой',
            'ration_name.max' => 'Название рациона должно быть не более 255 символов',
            'cooking_day_before.required' => 'Поле обязательно для заполнения',
            'cooking_day_before.boolean' => 'Поле должно быть boolean',
        ];
    }
}
