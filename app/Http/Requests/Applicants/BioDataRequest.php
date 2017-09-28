<?php

namespace NTI\Http\Requests\Applicants;

use Illuminate\Foundation\Http\FormRequest;

class BioDataRequest extends FormRequest
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
            'state_origin' => 'required',
            'lga_origin' => 'required',
            'state_residence' => 'required',
            'lga_residence' => 'required',
            'address' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required'
        ];
    }
}
