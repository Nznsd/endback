<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;
use NTI\Models\Applicant;
use NTI\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use NTI\Models\EducationBackground;
use NTI\Models\Grade;
use NTI\Models\Subject;
use NTI\Models\WorkExperience;
use NTI\Models\Upload;
use NTI\Repository\Services\NTI as NTIService;

class ApplicantReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        $this->middleware('role:applicant');
    }

    private static function getData($applicant)
    {
        $data['programme'] = NTIService::getInfo('programmes', 'id', $applicant->programme_id);
        $data['first_choice'] = NTIService::getInfo('specializations', 'id', $applicant->first_choice);
        $data['second_choice'] = NTIService::getInfo('specializations', 'id', $applicant->second_choice);
        $data['study_center'] = NTIService::getInfo('study_centers', 'id', $applicant->study_center_id);
        $data['soo'] = NTIService::getInfo('states', 'id', $applicant->soo);
        $data['soo_lga'] = NTIService::getInfo('lga', 'id', $applicant->soo_lga);
        $data['sor'] = NTIService::getInfo('states', 'id', $applicant->sor);
        $data['sor_lga'] = NTIService::getInfo('lga', 'id', $applicant->sor_lga);
        $data['academicSessionInfo'] = NTIService::getCurrentAcademicSessionInfo();
        
        return $data;
    }
    public function getReview()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();

        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();
        
        $data = self::getData($applicant);

        return view('applicants.review', 
            [
                'applicant' => $applicant, 
                'passport' => $passport, 
                'data' => $data,
                'page' => 'review',
                ]);
    }

    public function postReview(Request $request)
    {
       $appstate = 9;

       $applicant = Applicant::where('user_id', Auth::id())->first();

        if($request->certify_info)
        {
            $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;            

            $applicant->application_status = true;

            $applicant->save();

            return redirect()->route('dashboard');

        }

        return back()
            ->with('status', 'Please check the box to certify your Application!');
    }

    public function printApplicationForm()
    {
        $semesterId = NTIService::getCurrentAcademicSessionInfo()->semesterId;

        $applicant = Applicant::where('user_id', Auth::id())->first();

        $transaction = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => 2,          // make sure it is the transaction for admission form fee
            'semester_id' => $semesterId // for current semester
        ])->first();
        
        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();
        
        $data = self::getData($applicant);

        $tertiary = EducationBackground::where([
            ['param', 'applicant'],
            ['val', $applicant->id],
            ['certificate_name', 'tertiary'],
        ])->latest()->limit(2)->get();
        $o_level = EducationBackground::where([
            ['param', 'applicant'],
            ['val', $applicant->id],
            ['certificate_name', 'o_level'],
        ])->latest()->limit(2)->get();

        $grades = Grade::all();
        $subjects = Subject::all();

        $work_experience = WorkExperience::where([
            'param' => 'applicant',
            'val' => $applicant->id,
        ])->latest()->limit(2)->get();
            
        return view('applicants.print.application-form',[
            'applicant' => $applicant,
            'passport' => $passport,
            'data' => $data,
            'tertiary' => $tertiary,
            'o_level' => $o_level,
            'grades' => $grades,
            'subjects' => $subjects,
            'work_experience' => $work_experience,
            'transaction' => $transaction,
        ]);
    }
}
