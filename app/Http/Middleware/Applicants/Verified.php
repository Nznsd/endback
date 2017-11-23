<?php

namespace NTI\Http\Middleware\Applicants;

use Closure;
use NTI\Models\Applicant;
use NTI\Models\Transaction;
use NTI\Repository\Services\NTI as NTIService;
use NTI\Repository\Modules\ApplicantsModule;

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
        $payment = ApplicantsModule::getTransaction($applicant, 'application form', $academicSemesterId);
        $transaction = $payment['transaction'];
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
