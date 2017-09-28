<?php

namespace NTI\Http\Middleware;

use Closure;
use NTI\Models\Applicant;
use NTI\Models\Transaction;
use NTI\Repository\Services\NTI as NTIService;

class Verified
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
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
		$academicSessionId = $academicSessionInfo->sessionId;
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $transaction = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => 2,          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();
        if(!isset($transaction))
        {
            return redirect('applicants/programme')
                ->with('status', 'Please select a programme 
                    and pay your application fees before proceeding with your application');
        }else if($transaction->status == 'unpaid')
        {
            return redirect('applicants/payments')
            ->with('status', 'Your Payment has not been verified, please pay your application fees before proceeding with your application');
        }

        return $next($request);
    }
}
