<?php

namespace App\Http\Requests;

use App\Models\Reference;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RatesRequest extends FormRequest
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
            'dateStart' => ['date', 'date_format:Y-m-d'],
            'dateEnd' => ['date', 'date_format:Y-m-d'],
            'baseCode' => ['required', Rule::exists(Reference::class, 'code')],
            'compareCode' => ['required', Rule::exists(Reference::class, 'code')],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator Request validator.
     */
    public function withValidator(\Illuminate\Validation\Validator $validator)
    {
        $validator->sometimes('dateStart', 'before_or_equal:dateEnd', function ($input) {
            return !empty($input->dateEnd);
        });

        $validator->sometimes('dateEnd', 'after_or_equal:dateStart', function ($input) {
            return !empty($input->dateEnd);
        });
    }
}
