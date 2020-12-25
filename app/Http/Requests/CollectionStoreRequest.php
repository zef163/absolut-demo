<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectionStoreRequest extends FormRequest
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
     * @param RatesRequest $request
     * @return array
     */
    public function rules(RatesRequest $request)
    {
        return array_merge($request->rules(), [
            'note' => ['string', 'max:255'],
        ]);
    }
}
