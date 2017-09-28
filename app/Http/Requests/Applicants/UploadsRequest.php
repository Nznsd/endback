<?php

namespace NTI\Http\Requests\Applicants;

use Illuminate\Foundation\Http\FormRequest;

class UploadsRequest extends FormRequest
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
            'file' => 'file|mimes:jpeg,bmp,png,gif,pdf|size:100' // file means file must be uploaded, size 100kb
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
            'file.file' => 'couldnt upload file',
            'file.mimes' => 'please upload a valid jpeg or png file'
        ];
    }
}
