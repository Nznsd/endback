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
            'sittings.first' => 'required|array',
            'sittings.*.subjects' => 'required|array',
            'sittings.*.grades' => 'required|array',
            // same subject cannot be selected more than once(distinct) and field cannot be empty(alpha_num)
            'sittings.first.subjects.*' => 'required|distinct|alpha_num',
            'sittings.second.subjects.*' => 'nullable|distinct|alpha_num',
            'sittings.third.subjects.*' => 'nullable|distinct|alpha_num',
            // same grades fields cannot be empty(alpha_num)
            'sittings.first.grades.*' => 'required|alpha_num',
            'sittings.second.grades.*' => 'nullable|alpha_num',
            'sittings.third.grades.*' => 'nullable|alpha_num',
            // file uploads
            'sittings.*.certificate' => 'required|file',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'array' => 'please fill in completely all the values',
            'size' => ':attribute selected must be 9',
            'distinct' => 'subjects selected must be distinct',
            'file' => 'invalid file',
            'alpha_num' => 'unselected fields are not allowed',
            'sittings.*.certificate.required' => 'Certificate Upload is Required',
            'sittings.*.subjects.required' => 'Subjects is Required',
            'sittings.*.subjects.*.required' => 'All subjects fields must be entered',
            'sittings.*.grades.required' => 'Grades is Required',
            'sittings.*.grades.*.required' => 'All grades fields must be entered',
        ];
    }
}
