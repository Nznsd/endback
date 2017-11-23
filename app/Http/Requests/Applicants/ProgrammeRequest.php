<?php

namespace NTI\Http\Requests\Applicants;

use Illuminate\Foundation\Http\FormRequest;

class ProgrammeRequest extends FormRequest
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
            'programme' => 'required|in:1,2,3,4,5',
            'first_choice' => 'required|integer',
            'second_choice' => 'required|integer',
            'residence' => 'required',
            'study_center' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'in' => ':attribute must be a valid programme',
            'integer' => 'please choose both first and second choice'
        ];
    }
}
