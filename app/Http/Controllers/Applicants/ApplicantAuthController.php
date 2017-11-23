<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Support\Facades\Auth;
use NTI\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use NTI\Http\Requests\Applicants\CreateApplicantRequest; // form validation class

use NTI\Models\User;
use NTI\Models\Applicant;
use Carbon\Carbon;
use NTI\Repository\Services\NTI as NTIService;

class ApplicantAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/applicants/programme';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

    public function apply()
    {
        return view('applicants.register', ['page' => 'register']);
    }

    public function loginGet()
    {
        return view('applicants.login', ['page' => 'login']);
    }

    public function create(CreateApplicantRequest $request)
    {
        //dd($request->all());
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        
        $academicSemesterId = $academicSessionInfo->semesterId;

        // create new user and save
        $user = new User;

        $user->email = $request->email;

        $user->password = bcrypt($request->password_try);

        $user->role = 'applicant';

        $user->save();

        // create new applicant and save
        $applicant = new Applicant;

        if($academicSessionInfo->semester == 2)
        {
            $applicant->semester_id = $academicSemesterId + 1;
        } 
        else
        {
            $applicant->semester_id = $academicSemesterId;
        }

        $applicant->user_id = $user->id;

        $applicant->application_state = 1;

        $applicant->firstname = $request->firstname;

        $applicant->surname = $request->lastname;

        $applicant->othername = $request->middlename;

        $applicant->email = $request->email;

        $applicant->phone = $request->phone;

        $applicant->gender = $request->gender;

        $applicant->marital_status = $request->maritalstatus;

        $applicant->dob = //$request->birthdate;
        Carbon::createFromFormat('d/m/Y', $request->birthdate)->format('Y-m-d');

        $applicant->save();

        // log new user in and redirect
        Auth::login($user);

        return redirect()->route('programme');
    }


    protected function redirectTo()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();

        switch($applicant->application_state)
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
                return '/applicants/personal-information';
                break;
            }
            case 5: 
            {
                return '/applicants/certificate';
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
            case 8: 
            {
                return '/applicants/review';
                break;
            }
            case 9:
            {
                return '/applicants/dashboard';
                break;
            }
            default:
            {
                return '/';
            }
        }
    }

    protected function handleRedirect($id)
    {
        $applicant = Applicant::where('user_id', $id)->first();

        switch($applicant->application_state)
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
