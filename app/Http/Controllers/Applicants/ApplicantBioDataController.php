<?php

namespace NTI\Http\Controllers\applicants;

use NTI\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use NTI\Http\Requests\Applicants\BioDataRequest; // form validation class
use NTI\Models\User;
use NTI\Models\Applicant;
use NTI\Models\State;
use NTI\Models\LGA;
use NTI\Models\Upload;

class ApplicantBioDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function getPersonalInfo()
    {
        $states = State::all();
        
        $applicant = Applicant::where('user_id', Auth::id())->first();

        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        return view('applicants.biodata', 
            [
                'applicant' => $applicant, 
                'states' => $states, 
                'passport' => $passport,
                'page' => 'biodata',
            ]);
    }

    public function postPersonalInfo(BioDataRequest $request)
    {
        $appstate = 5;

        $applicant = Applicant::where('user_id', Auth::id())->first();

        $applicant->soo = $request->state_origin;

        $applicant->sor = $request->state_residence;

        $applicant->soo_lga = $request->lga_origin;

        $applicant->sor_lga = $request->lga_residence;

        $applicant->address = $request->address;

        $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;

        $applicant->save();

        return redirect()->route('certificate');
    }
}
