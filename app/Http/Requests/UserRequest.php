<?php

namespace App\Http\Requests;

class UserRequest extends Request
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
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'loan_term' => 'required',
            'interest_rate' => 'required',
        ];

        if( $this->method() == 'PUT' ) {
            $rules['email'] .= ','.$this->get('id');
            $rules['phone'] .= ','.$this->get('id');
        }

        return $rules;
    }
}
