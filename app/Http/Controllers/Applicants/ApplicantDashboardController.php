<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;

use NTI\Models\Applicant;
use Illuminate\Support\Facades\Auth;
use NTI\Models\Upload;
use NTI\Models\Transaction;
use NTI\Repository\Services\NTI as NTIService;
use NTI\Repository\Modules\ApplicantsModule;

class ApplicantDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:applicant']);
        $this->middleware('verified');
    }

    public function getDashboard()
    {
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', Auth::id())->first();
        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        // get all transactions for this applicant
        $payment = ApplicantsModule::getTransaction($applicant, 'application form', $academicSemesterId);
        $transaction_application = $payment['transaction'];
        $transaction_application->desc = NTIService::getInfo('fee_types', 'id', $transaction_application->fee_id)->name;

        $payment = ApplicantsModule::getTransaction($applicant, 'tuition', $academicSemesterId);
        $transaction_tuition = $payment['transaction'];
        if(isset($transaction_tuition))
            $transaction_tuition->desc = NTIService::getInfo('fee_types', 'id', $transaction_tuition->fee_id)->name;

        $payments = [];
        $payments['total'] = $transaction_tuition ? 
            $transaction_tuition->amount + $transaction_application->amount :
            $transaction_application->amount;
        $payments['feeslist']['application'] =  $transaction_application;
        if(isset($transaction_tuition))
        $payments['feeslist']['tuition'] =  $transaction_tuition;
        $admission = NTIService::getInfo('admissions', 'applicant_id', $applicant->id);

        return view('applicants.dashboard', 
            [
                'applicant' => $applicant,
                'payments' => $payments,
                'admission' => $admission,
                'passport' => $passport,
            ]);
    }

    public function printAdmissionForm()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();
        if(!$applicant->application_status)
        {
            abort(403);
        }
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        $data = ApplicantsModule::getData($applicant);

        $transaction_tuition = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => 19,          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();

        return view('applicants.print.admission-letter', 
        [
            'applicant' => $applicant,
            'passport' => $passport,
            'data' => $data,
            //'payments' => $payments,
            //'transaction_application' => $transaction_application,
            'transaction_tuition' => $transaction_tuition,
        ]);
    }

    public function getReceipts()
    {
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', Auth::id())->first();
        $passport = Upload::where(
            [
                'param' => 'applicant',
                'val' => $applicant->id,
                'object_name' => 'photo',
            ]
        )->latest()->first();

        $payment = ApplicantsModule::getTransaction($applicant, 'application form', $academicSemesterId);
        $transaction_application = $payment['transaction'];

        $payment = ApplicantsModule::getTransaction($applicant, 'tuition', $academicSemesterId);
        $transaction_tuition = $payment['transaction'];

        $data['academicSessionInfo'] = $academicSessionInfo;

        return view('applicants.print.dashboard-receipts',
        [
            'applicant' => $applicant,
            'passport' => $passport,
            'transaction_application' => $transaction_application,
            'transaction_tuition' => $transaction_tuition,
            'data' => $data,
        ]);
    }
}
