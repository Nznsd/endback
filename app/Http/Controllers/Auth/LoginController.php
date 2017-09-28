<?php

namespace NTI\Http\Controllers\Auth;

use NTI\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use NTI\Models\Applicant;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/applicants/programme';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
                return '/applicants/uploads';
                break;
            }
            case 7: 
            {
                return '/applicants/experience';
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
