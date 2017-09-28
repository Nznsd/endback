<?php

namespace NTI\Http\Requests\Applicants;

use Illuminate\Foundation\Http\FormRequest;

class CreateApplicantRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password_try' => 'required|min:8',
            'firstname' => 'required|min:2',
            'lastname' => 'required|min:2',
            'middlename' => 'nullable|min:2',
            'phone' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'maritalstatus' => 'required|in:married,single,divorced,widowed',
            'birthdate' => 'required|date'
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
            'required' => 'the :attribute field is required',
            'email' => 'please provide a valid email address',
            'unique' => 'selected :attribute already exists, please choose another valid :attribute address',
            'min' => ':attrubute must be at least :min characters',
            'password.confirmed' => 'please confirm your password again',
            'numeric' => 'invalid phone number',
            'in'      => 'The :attribute must be one of the following types: :values',
            'date' => 'invalid date of birth',
        ];
    }
}
