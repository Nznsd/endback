<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;

use NTI\Http\Requests\Applicants\ProgrammeRequest; // form validation class
use NTI\Models\Applicant;
use NTI\Repository\Services\NTI as NTIService;

// use \PDF;

class ApplicantProgrammeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:applicant', 'payment']);
    }

    public function programmeGet(Request $request)
    {
        $programmes = NTIService::getProgrammes();

        $states = NTIService::getStates();

        $applicant = Applicant::where('user_id', $request->user()->id)->first();

        return view('applicants.programme', 
            [
                'applicant' => $applicant, 
                'programmes' => $programmes, 
                'states' => $states
            ]);
    }

    public function programmePost(ProgrammeRequest $request)
    {
        // save programme details
        $appstate = 2;
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $applicant = Applicant::where('user_id', $request->user()->id)->first();

        $applicant->programme_id = $request->programme;
        $applicant->entry_level = $request->p_entry_year ? $request->p_entry_year : 1;
        if($request->p_entry_year > 1)
        {
            $applicant->entry_type = 'DE';  
        }
        $applicant->entry_semester = $academicSessionInfo->semester;
        $applicant->first_choice = $request->first_choice;
        $applicant->second_choice = $request->second_choice;
        $applicant->sor = $request->residence;
        $applicant->study_center_id = $request->study_center;
        $applicant->app_no = NTIService::generateApplicantNo($academicSessionInfo->semesterId,
                                 $request->programme, $academicSessionInfo->year);
        $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;
        $applicant->save();

        return redirect('applicants/payments');
    }
}
