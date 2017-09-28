<?php

namespace NTI\Http\Controllers\Auth;

use NTI\User;
use NTI\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \NTI\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
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
