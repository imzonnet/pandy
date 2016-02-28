<?php

namespace App\Http\Requests;

class InfoRequest extends Request
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
            'loan_amount' => 'required',
            'interest_rate' => 'required',
            'switching_costs' => 'required',
            'ongoing_costs' => 'required',
        ];
    }
}
