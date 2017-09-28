<?php

namespace NTI\Http\Controllers\Auth;

use NTI\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

use NTI\Models\Applicant;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function redirectTo()
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
}
