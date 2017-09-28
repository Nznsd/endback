<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;

use NTI\Models\Applicant;
use Illuminate\Support\Facades\Auth;
use NTI\Models\Upload;
use NTI\Models\Transaction;
use NTI\Repository\Services\NTI as NTIService;

class ApplicantDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:applicant']);
        $this->middleware('verified');
    }

    private static function getData($applicant)
    {
        $data['programme'] = NTIService::getInfo('programmes', $applicant->programme_id);
        $data['first_choice'] = NTIService::getInfo('specializations', $applicant->first_choice);
        $data['second_choice'] = NTIService::getInfo('specializations', $applicant->second_choice);
        $data['study_center'] = NTIService::getInfo('study_centers', $applicant->study_center_id);
        $data['soo'] = NTIService::getInfo('states', $applicant->soo);
        $data['soo_lga'] = NTIService::getInfo('lga', $applicant->soo_lga);
        $data['sor'] = NTIService::getInfo('states', $applicant->sor);
        $data['sor_lga'] = NTIService::getInfo('lga', $applicant->sor_lga);
        $data['academicSessionInfo'] = NTIService::getCurrentAcademicSessionInfo();
        return $data;
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
        $transaction_application = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => 2,          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();
        $transaction_application->desc = NTIService::getInfo('fee_types', $transaction_application->fee_id)->name;

        $transaction_tuition = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => 19,          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();
        if(isset($transaction_tuition))
            $transaction_tuition->desc = NTIService::getInfo('fee_types', $transaction_tuition->fee_id)->name;

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

        $data = self::getData($applicant);

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

        $transaction_application = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => 2,          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();

        $transaction_tuition = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => 19,          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();

        $data['academicSessionInfo'] = NTIService::getCurrentAcademicSessionInfo();

        return view('applicants.print.dashboard-receipts',
        [
            'applicant' => $applicant,
            'transaction_application' => $transaction_application,
            'transaction_tuition' => $transaction_tuition,
            'data' => $data,
        ]  
    );
    }
}
