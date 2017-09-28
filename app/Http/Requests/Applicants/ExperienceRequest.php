<?php

namespace NTI\Http\Requests\Applicants;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'employer' => 'required',
            'position' => 'required',
            'job_description' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after:from_date'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'date' => ':attribute must be a valid date',
            'after' => 'end date must be after start date',
        ];
    }
}
