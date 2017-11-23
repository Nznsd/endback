<?php

namespace NTI\Http\Controllers\applicants;

use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use NTI\Http\Requests\Applicants\CertificateRequest; // form validation class
use NTI\Models\Applicant;
use NTI\Models\Upload;
use NTI\Models\Certificate;
use NTI\Models\EducationBackground;
use NTI\Models\Subject;
use NTI\Models\Grade;
use NTI\Models\State;
use NTI\Models\LGA;
use NTI\Repository\Services\NTI as NTIService;

class ApplicantCertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        $this->middleware('role:applicant');
    }

    public function getEducationalInfo($cert = null)
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();
        
        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        $certificates = Certificate::all();
                
        $years = range(1970, date('Y'));

        $education = EducationBackground::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'certificate_name' => isset($cert) ? $cert : 'o_level',
            ]
        )->latest()->first();
        
        if(!isset($cert) || $cert == 'o_level')
        {
            $subjects = Subject::all();
            $grades = Grade::where([
                ['certificate_id', 1],
                ['type_id', 1],
            ])->get();
            //NTIService::getInfo('grades', 'certificate_id', 1);
            
            $states = State::all();
            $lgas = LGA::all();

            return view('applicants.certificates',
                [
                    'applicant' => $applicant, 'passport' => $passport, 'certificates' => $certificates,
                    'subjects' => $subjects, 'grades' => $grades, 'states' => $states, 
                    'years' => $years, 'page' => 'certificate', 'education' => $education,
                    'lgas' => $lgas,
                ]);
        } else if($cert == 'tertiary')
        {
            $grades = Grade::where([
                ['certificate_id', 2],
                ['type_id', 1],
            ])->get();
    
            return view('applicants.cert-degree', 
                [
                    'applicant' => $applicant, 'passport' => $passport, 'certificates' => $certificates,
                    'grades' => $grades, 
                    'years' => $years,
                    'page' => 'certificate',
                    'education' => $education,
                ]);
        }

    }

    public function handleExam(CertificateRequest $request, $cert)
    {
        $appstate = 6;
       //dd($request->all());
        $applicant = Applicant::where('user_id', $request->user()->id)->first();

        foreach($request->sittings as $sitting)
        {
            $ed = new EducationBackground;
            
            $ed->param = 'applicant';
    
            $ed->val = $request->user()->id;
    
            $ed->certificate_name = $cert; // ssce, degree etc
            
            if($cert == 'tertiary')
            {
                $ed->school = json_encode([
                    "name" => $sitting['school_name'],
                    "year" => $sitting['year'],
                    "type" => $sitting['result_type'],
                ]); // json containing name, year, lga, state and type(waec|neco)
            } else
                $ed->school = json_encode([
                    "name" => $sitting['school_name'],
                    "state" => $sitting['state'],
                    "lga" => $sitting['lga'],
                    "year" => $sitting['year'],
                    "type" => $sitting['result_type'],
                ]); // json containing name, year, lga, state and type(waec|neco)
    
            
            $ed->grades = json_encode(
                array_combine($sitting['subjects'], $sitting['grades'])
            ); // json of eng, math etc
            
    
            $ed->save();

            // store uploaded file
            if($sitting['certificate']->isValid())
            {
                $path = Storage::putFile('public/applicants/' . $applicant->id, $sitting['certificate'], 'public');
                //$sitting['certificate']->store('public/applicants/' . $applicant->id);
                
                //$path = str_replace('public/', '/storage/', $path);
    
                $upload = new Upload;
    
                $upload->object_name = 'certificate';
    
                $upload->param = 'applicant';
    
                $upload->val = $applicant->id;
    
                $upload->src = $path;
    
                $upload->save();
            }
        }

        if($cert == 'o_level')
        {
            return redirect('applicants/certificate/tertiary');
        }

        $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;
        
                $applicant->save();

        return redirect()
            ->route('experience');
    }
}