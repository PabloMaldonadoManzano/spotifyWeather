<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WeatherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            "city" => [
                'role_id' => Rule::requiredIf($this->query('lat') == null && $this->query('lon') == null),
            ],
            "lon" => [
                'role_id' => Rule::requiredIf($this->query('city') == null),
            ],
            "lat" => [
                'role_id' => Rule::requiredIf($this->query('city') == null),
            ]

        ];

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'city.required' => 'El parametro city es requerido.',
            'lat.required' => 'El parameto lat es requerido.',
            'lon.required' => 'El parameto lon es requerido.',
        ];
    }
}
