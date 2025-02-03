<?php

namespace App\Http\Requests\Order;

use App\Enum\DeliveryScheduleType;
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
            'client_name' => [
                'required',
                'string',
                'max:255',
            ],
            'client_phone' => [
                'required',
                'string',
                'max:21',
                'unique:orders,client_phone',
            ],
            'tariff' => [
                'required',
                'integer',
                'exists:tariffs,id',
            ],
            'delivery_schedule_type' => [
                'required',
                'string',
                'in:' . implode(',', DeliveryScheduleType::values()),
            ],
            'comment' => [
                'nullable',
                'string',
            ],
            'first_date' => [
                'required',
                'date',
                'after_or_equal:today',
//                'before:last_date',
            ],
            'last_date' => [
                'required',
                'date',
                'after_or_equal:first_date',
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'tariff.exists' => 'Тариф не найден',
            'tariff.required' => 'Не выбран тариф',
            'delivery_schedule_type.in' => 'Тип доставки не найден',
            'delivery_schedule_type.required' => 'Не выбран тип доставки',
            'client_name.required' => 'Имя обязательно для заполнения',
            'client_name.string' => 'Имя должно быть строкой',
            'client_name.max' => 'Имя не должно превышать 255 символов',
            'client_phone.required' => 'Телефон обязательно для заполнения',
            'client_phone.string' => 'Телефон должно быть строкой',
            'client_phone.max' => 'Телефон не должно превышать 21 символ',
            'client_phone.unique' => 'Заказ с этим номером телефона уже используется',
            'first_date.required' => 'Дата от обязательна для заполнения',
            'first_date.date' => 'Дата от должна быть датой',
            'first_date.after_or_equal' => 'Дата от должна быть больше или равна сегодняшней дате: ' . date('d.m.Y'),
            'first_date.before' => 'Дата от должна быть меньше даты до',
            'last_date.required' => 'Дата до обязательна для заполнения',
            'last_date.date' => 'Дата до должна быть датой',
            'last_date.after_or_equal' => 'Дата до должна быть больше или равна дате от',
        ];
    }
}
