<?php

namespace NTI\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use NTI\Models\User;
use NTI\Models\Student;
use NTI\Models\Applicant;
use NTI\Models\Transaction;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

	public function confirmRRR(Request $request)
	{
		$user = User::where('email', $request->email)->first();
		
		if(!isset($user))
		{
			return back()
				->with('status', 'Email not found');
		}

		$transaction = Transaction::where('remitaBefore->RRR', $request->rrr)->first();

		if(!isset($transaction))
		{
			return back()
				->with('status', 'RRR not found');
		}

		if($user->role == 'applicant')
		{
			$person = Applicant::where('user_id', $user->id)->first();
		} else if($user->role == 'student')
		{
			$person = Student::where('user_id', $user->id)->first();
		} else {
			return back()
				->with('status', 'Admin account detected!');
		}
		
		if($person->id != $transaction->val)
		{
			return back()
				->with('status', 'Credentials do not match!');
		}
		$id = base64_encode($user->id);
		//return view('auth.passwords.reset', ['person' => $person]);
		return redirect('/password/confirm-rrr/' . $id);
	}

	public function getRRR(Request $request, $eid)
	{
		if(!isset($eid))
		{
			return redirect('password/reset');
		}

		$id = base64_decode($eid);

		return view('auth.passwords.reset', ['id' => $id]);
	}

	public function change(Request $request)
	{
		$this->validate($request, [
			'password' => 'required|min:8|confirmed',
			'email' => 'required|email|exists:users,email'
		]);
		$user = User::where('email', $request->email)->first();
		$user->password = bcrypt($request->password);
		$user->save();
		Auth::loginUsingId($user->id);
			return redirect('/home');
	}
}
