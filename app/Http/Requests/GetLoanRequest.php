<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetLoanRequest extends FormRequest
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
        return [
            'amount' => [
                'required','integer','max:1000000','min:100'
            ],
            'term' => [
                'required','integer','min:2','max:2000'
            ]
        ];
    }
}
