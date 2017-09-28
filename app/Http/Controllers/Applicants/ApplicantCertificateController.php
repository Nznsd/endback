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
use NTI\Repository\Services\NTI as NTIService;

class ApplicantCertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function getEducationalInfo($type = null)
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();
        
        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        $certificates = NTIService::getInfo('certificates');
                
        $years = range(1970, date('Y'));
        
        if(!isset($type) || $type == 'o_level')
        {
                    $subjects = NTIService::getInfo('subjects');
                    $grades = NTIService::getInfo('grades', 'certificate_id', 1);
                    $states = NTIService::getInfo('states');
            
                    return view('applicants.certificates',
                        [
                            'applicant' => $applicant, 'passport' => $passport, 'certificates' => $certificates,
                            'subjects' => $subjects, 'grades' => $grades, 'states' => $states, 
                            'years' => $years, 'page' => 'certificate',
                        ]);
        } else if($type == 'tertiary')
        {
            $grades = NTIService::getInfo('grades', 'certificate_id', 2);
            
            $majors = [
                'BSc' => 1,
                'BEd' => 2,
                'BA' => 3,
                'BEng' => 4,
            ];
    
            return view('applicants.cert-degree', 
                [
                    'applicant' => $applicant, 'passport' => $passport, 'certificates' => $certificates,
                    'grades' => $grades, 
                    'years' => $years,
                    'majors' => $majors,
                    'page' => 'certificate',
                ]);
        }

    }

    public function handleExam(CertificateRequest $request, $cert)
    {
        $appstate = 6;
        // dd($request->all());
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