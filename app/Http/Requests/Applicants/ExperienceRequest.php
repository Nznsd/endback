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
            'workplaces' => 'required|array',
            'workplaces.*.employer' => 'required',
            'workplaces.*.position' => 'required',
            'workplaces.*.job_description' => 'required',
            'workplaces.*.from_date' => 'required|date',
            'workplaces.*.to_date' => 'nullable|date|after:from_date'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'workplaces.*.employer.required' => 'Employer is required',
            'workplaces.*.position.required' => 'Position is required',
            'workplaces.*.job_description.required' => 'Job description is required',
            'date' => 'invalid date',
            'after' => 'end date must be after start date',
        ];
    }
}
