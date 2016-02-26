<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BankRequest extends Request
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
            'name' => 'required',
            'interest_rate' => 'required',
            'comparison_rate' => 'required',
            'annual_fees' => 'required',
            'monthly_fees' => 'required',
            'special' => 'required',
        ];
    }
}
