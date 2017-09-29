<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;

use NTI\Http\Requests\Applicants\ExperienceRequest; // form validation class
use Illuminate\Support\Facades\Auth;
use NTI\Models\Applicant;
use NTI\Models\WorkExperience;
use NTI\Models\Upload;
use \Carbon\Carbon;

class ApplicantExperienceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function getExperience()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();

        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        $experience = WorkExperience::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
            ]
        )->latest()->first();

        return view('applicants.experience', 
            [
                'applicant' => $applicant, 
                'passport' => $passport,
                'page' => 'experience',
                'experience' => $experience,
            ]);
    }

    public function postExperience(ExperienceRequest $request)
    {
        $appstate = 7;

        $applicant = Applicant::where('user_id', Auth::id())->first();

        $exp = new WorkExperience;

        $exp->param = 'applicant';

        $exp->val = $applicant->id;

        $exp->employer = $request->employer;

        $exp->position = $request->position;

        $exp->desc = $request->job_description;

        $exp->startDate = Carbon::createFromFormat('m/d/Y', $request->from_date)->format('Y-m-d');

        $exp->endDate = Carbon::createFromFormat('m/d/Y', $request->to_date)->format('Y-m-d');

        $exp->save();

        $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;        

        $applicant->save();

        return redirect()->route('uploads');
    }
}