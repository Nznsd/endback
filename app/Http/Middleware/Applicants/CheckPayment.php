<?php

namespace NTI\Http\Middleware\Applicants;

use Closure;
use NTI\Models\Applicant;
use NTI\Models\Transaction;
use NTI\Repository\Services\NTI as NTIService;

class CheckPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    // this middleware checks if payment has been made for a particular programme and if true, 
    // protects the route so programme cannot be changed again

        $fee_id = $request->is('*tuition') ? 19 : 2;
        $redirect = $request->is('*tuition') ? 'applicants/dashboard/receipts' : 'applicants/personal-information';
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $transaction = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => $fee_id,          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();
		
        //if(isset($transaction) && isset($transaction->remita_after) && json_decode($transaction->remita_after)->message == 'Approved')
        if(isset($transaction) && $transaction->status == 'paid')
		{
			return redirect($redirect)
                ->with('status', 'Payment has been made for selected programme
                    , please contact support if you want to change it.');
		}
		
        return $next($request);
    }
}
