<?php

namespace NTI\Http\Requests\Applicants;

use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
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
            'sittings' => 'required|array',
            'sittings.*.school_name' => 'required',
            'sittings.*.year' => 'required|alpha_num',
            'sittings.*.result_type' => 'required|alpha_num',
            // first sitting required, others can be null
            'sittings.first' => 'required|array',
            'sittings.second' => 'nullable|array',
            'sittings.third' => 'nullable|array',
            //
            'sittings.first.subjects' => 'required|array',
            'sittings.second.subjects' => 'nullable|array',
            'sittings.third.subjects' => 'nullable|array',
            //
            'sittings.first.grades' => 'required|array',
            'sittings.second.grades' => 'nullable|array',
            'sittings.third.grades' => 'nullable|array',
            // same subject cannot be selected more than once(distinct) and field cannot be empty(required)
            'sittings.first.subjects.*' => 'distinct|required',
            'sittings.second.subjects.*' => 'nullable|distinct',
            'sittings.third.subjects.*' => 'nullable|distinct',
            // same grades fields cannot be empty(alpha_num)
            'sittings.first.grades.*' => 'required',
            'sittings.second.grades.*' => 'nullable',
            'sittings.third.grades.*' => 'nullable',
            // file uploads
            'sittings.*.certificate' => 'required|file',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'array' => 'Please fill in completely all the values',
            'distinct' => 'Please do not select the same subject more than once',
            'file' => 'Invalid File',
            'alpha_num' => 'Unselected fields are not allowed',
            'sittings.*.certificate.required' => 'Certificate Upload is required',
            'sittings.*.subjects.required' => 'Subjects is required',
            'sittings.*.subjects.*.required' => 'All subjects/majors fields must be entered',
            'sittings.*.grades.required' => 'Grades is Required',
            'sittings.*.grades.*.required' => 'All grades fields must be entered',
            'sittings.*.school_name.required' => 'School or Institution name is required',
            'sittings.*.year.alpha_num' => 'Year is required',
            'sittings.*.result_type.alpha_num' => 'Result Type is required',
        ];
    }
}
