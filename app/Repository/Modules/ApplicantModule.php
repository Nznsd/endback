<?php
namespace NTI\Repository\Modules;

use NTI\Models\Applicant;

class ApplicantModule 
{
      public function __construct()
      {

      }

      public static function redirectTo()
      {
        $applicant = Applicant::where('user_id', Auth::id())->first();

        switch($applicant->application_step)
        {
            case 1: 
            {
                return '/applicants/programme';
                break;
            }
            case 2: 
            {
                return '/applicants/payments';
                break;
            }
            case 3: 
            {
                return '/applicants/verify';
                break;
            }
            case 4: 
            {
                return '/applicants/biodata';
                break;
            }
            case 5: 
            {
                return '/applicants/certificates';
                break;
            }
            case 6: 
            {
                return '/applicants/experience';
                break;
            }
            case 7: 
            {
                return '/applicants/uploads';
                break;
            }
            default: 
            {
                return '/applicants/review';
                break;
            }
        }
    }

    protected function handleRedirect($id)
    {
        $applicant = Applicant::where('user_id', $id)->first();

        switch($applicant->application_step)
        {
            case 1: 
            {
                return redirect()->route('programme');
                break;
            }
            case 2: 
            {
                return redirect()->route('payments');
                break;
            }
            case 3: 
            {
                return redirect()->route('verify');
                break;
            }
            case 4: 
            {
                return redirect()->route('biodata');
                break;
            }
            case 5: 
            {
                return redirect()->route('certificates');
                break;
            }
            case 6: 
            {
                return redirect()->route('experience');
                break;
            }
            case 7: 
            {
                return redirect()->route('uploads');
                break;
            }
            default: 
            {
                return redirect()->route('review');
                break;
            }
        }
    }
}