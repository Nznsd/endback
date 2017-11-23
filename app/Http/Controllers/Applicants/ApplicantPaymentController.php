<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use NTI\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use NTI\Models\Applicant;
use NTI\Models\Programme;
use NTI\Models\Specialization;
use NTI\Models\FeeDefinition;
use NTI\Models\Transaction;
use NTI\Models\Upload;
use NTI\Repository\Services\NTI as NTIService;
use NTI\Repository\Services\Remita as RemitaService;
use NTI\Repository\Libraries\HTMLPDF as PDFService;
use NTI\Repository\Modules\ApplicantsModule;


class ApplicantPaymentController extends Controller
{
    protected $academicSessionId;
    protected $academicSemesterId;

    public function __construct()
    {
       $this->middleware(['auth', 'role:applicant']);
       $this->middleware('payment')->only('pay');
    }

    public function pay(Request $request, $type = null)
    {
        // set up required variables
        $appstate = 3;
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $payment = ApplicantsModule::getTransaction($applicant, $type, $academicSemesterId);
        $transaction = $payment['transaction'];
        $fee_def = $payment['fee_def'];
        $fee_type = $payment['fee_type'];

         // construct payment parameters
         
         //$rem = new RemitaService('4430731');
         $rem = new RemitaService($fee_type->remitaServiceTypeId);
         $orderId = $rem->generateOrderId($applicant->id);
         $responseUrl = $rem->getResponseUrl('/applicants/remita-response/' . $type);
         $totalAmount = $fee_def->amount;
         $hash = $rem->generateHash1($orderId, $totalAmount, $responseUrl);
         $payerName = $applicant->surname . ' ' . $applicant->firstname. ' '.$applicant->othername;
         $payerEmail = $applicant->email;
         $payerPhone = $applicant->phone;
         $itemId1 = 'nti application fee';
         $itemId2 = 'omniswift commission';
         $beneficiaryAmount1 = $totalAmount - 500;
         $beneficiaryAmount2 = 500;

         $paymentDetails = [
             "orderId" => $orderId,
             "responseUrl" => $responseUrl,
             "totalAmount" => $totalAmount,
             "hash1" => $hash,
             "payerName" => $payerName,
             "payerEmail" => $payerEmail,
             "payerPhone" => $payerPhone,
             "itemId1" => $itemId1,
             "itemId2" => $itemId2,
             "beneficiaryAmount1" => $beneficiaryAmount1,
             "beneficiaryAmount2" => $beneficiaryAmount2
         ];
         
        if(!isset($transaction))
        {
            $response = $rem->getRemitaResponse($paymentDetails);
            // save payment parameters to db
            if(!isset($response) || !isset($response->RRR))
            {
                abort(500, 'We are unable to contact Remita at the moment, please try again after a few seconds...');
                return redirect('applicants/programme')->withInput()
                    ->with('status', 'We are unable to contact Remita at the moment, please try again after a few seconds...');
            }

            $transaction = new Transaction;
            $transaction->fee_id =  $fee_type->id;
            $transaction->param = 'applicant';
            $transaction->val = $applicant->id;
            $transaction->amount = $totalAmount;
            $transaction->installment = 0;
            $transaction->orderId = $orderId;
            $transaction->fee_table = 'fee_definitions';
            $transaction->fee_table_id = $fee_def->id;
            $transaction->semester_id = $academicSemesterId;
            $transaction->remitaBefore = json_encode($response);
            $transaction->save();
        } else if($transaction->fee_table_id != $fee_def->id)
        {
            $response = $rem->getRemitaResponse($paymentDetails);
            // save payment parameters to db
            if(!isset($response) || !isset($response->RRR))
            {
                return redirect('applicants/programme')
                    ->with('status', 'We are unable to contact Remita at the moment, please try again after a few seconds...');
            }
            $transaction->amount = $totalAmount;
            $transaction->orderId = $orderId;
            $transaction->fee_table_id = $fee_def->id;
            $transaction->remitaBefore = json_encode($response);
            $transaction->save();
        }
        
        if(isset($transaction) && !isset($transaction->remitaAfter) 
            || $transaction->status !== 'paid')
        {
            $paymentDetails["orderId"] = $transaction->orderId;
            $paymentDetails["totalAmount"] = $transaction->amount;
            // $paymentDetails["hash1"] = $rem->generateHash1($transaction->orderId, $transaction->amount, $responseUrl);
            $response = json_decode($transaction->remitaBefore);
        }


        $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;
        $applicant->save();

        $programme = Programme::find($applicant->programme_id)->name;
        $specialization = Specialization::find($applicant->first_choice)->name;
        $remita = [
            'rrr' => $response->RRR,
            'merchantId' => $rem->merchantId,
            'hash' => $rem->generateHash2($response->RRR),
            'url' => $rem->gatewayRRRPaymentUrl,
        ];

        // display payment redirect page
        return view('applicants.payments', 
        [
            'applicant' => $applicant, 
            'programme' => $programme, 
            'specialization' => $specialization, 
            'paymentDetails' => $paymentDetails, 
            'remita' => $remita,
            'type' => $type,
        ]);
    }

    public function remitaResponse(Request $request, $type = null)
    {
        // after handling this, step becomes 3 so that any applicant on step 3 will be sent
        // to the verify RRR view.
        $transaction = Transaction::where('orderId', $request->orderID)
            ->orWhere('remitaBefore->RRR', $request->RRR)
            ->first();
        // construct json of remita payload
        $remita = [
            'orderId' => isset($request->orderID) ? $request->orderID : 'not set',
            'RRR' => isset($request->RRR) ? $request->RRR : 'not set'
        ];

        $transaction->remitaAfter = json_encode($remita);
        
        $transaction->save();

        return redirect('applicants/verify/' . $type);
    }

    public function verifyGet(Request $request, $type = null)
    {
        $appstate = 4;
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo(); 
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $payment = ApplicantsModule::getTransaction($applicant, $type, $academicSemesterId);
        $transaction = $payment['transaction'];
        if(!isset($transaction))
        {
            return redirect('applicants/programme');
        }

        if($transaction->status == 'unpaid')
        {
            $rem = new RemitaService('serviceTypeId');
            $rrr = json_decode($transaction->remitaBefore)->RRR;
            $response = $rem->verifyPayment('RRR', $rrr);
            $transaction->remitaAfter = isset($response) ? json_encode($response) : null;

            if(isset($response) && $response->status == "01")
            {
                $transaction->status = 'paid';
                $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;
                $applicant->save();
            }
    
            $transaction->save();

            // if payment is tuition, go to dashboard
            if($type == 'tuition' && $transaction->status == 'paid')
            {
                return redirect('applicants/dashboard')
                    ->with('status', 'Tuition Payment Verified, please await your student login credentials');
            }
        }

        $data = [
            'programme' => NTIService::getInfo('programmes', 'id', $applicant->programme_id)->name,
            'specialization' => NTIService::getInfo('specializations', 'id', $applicant->first_choice)->name,
        ];

        return view('applicants.verify', 
        [
            'applicant' => $applicant,
            'type' => $type,
            'transaction' => $transaction,
            'data' => $data,
        ]);
    }

    public function verifyPost(Request $request)
    {
        // verify RRR,
        // if payment complete, move to step 4: personal info, uploads etc
        $appstate = 4;
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo(); 
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $transaction = Transaction::where([
            ['remitaBefore->RRR', $request->rrr],
            ['param','applicant'],
            //['val', $applicant->id],
            ['semester_id', $academicSemesterId]
                ])->first();
        if(!isset($transaction))
        {
            return response()->json([
                'message' => 'Invalid RRR, Please check the number and try again...',
                'status' => '404',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST, Response::$statusTexts[Response::HTTP_BAD_REQUEST]);
        }
        $app = Applicant::find($transaction->val);
        
        if($transaction->status === 'unpaid')
        {
            $rem = new RemitaService();
            $rrr = $request->rrr;
            $response = $rem->verifyPayment('RRR', $rrr);
            $transaction->remitaAfter = isset($response) ? json_encode($response) : null;
    
            if(isset($response) && $response->status == "01")
            {
                $transaction->status = 'paid';
                $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;
                $applicant->save();
            }
    
            $transaction->save();
        }

        $hasPaid = $transaction->status == 'paid';

        return response()->json([
            'fullname' => $app->surname . " " . $app->firstname,
            'programme' => NTIService::getInfo('programmes', 'id', $app->programme_id)->name,
            'specialization' => NTIService::getInfo('specializations', 'id', $app->first_choice)->name,
            'paystatus' => $transaction->status,
            'amount' => $transaction->amount,
            'rrr' => json_decode($transaction->remitaAfter)->RRR,
            'orderid' => $transaction->orderId,
            'message' => json_decode($transaction->remitaAfter)->message,
            'status' => json_decode($transaction->remitaAfter)->status,
        ])->setStatusCode(($hasPaid ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST), ($hasPaid ? Response::$statusTexts[Response::HTTP_OK] : Response::$statusTexts[Response::HTTP_BAD_REQUEST]));
    }

    public function verified($type = null)
    {
        if($type == 'tuition')
        {
            return redirect('applicants/dashboard')
                ->with('status', 'Tuition Payment Verified, please await your student login credentials');
        }
        return redirect('applicants/personal-information')
            ->with('status', 'Payment Verified, please proceed with your application!');

    }

    public function getInvoice(Request $request, $type = null)
    {
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $payment = ApplicantsModule::getTransaction($applicant, $type, $academicSemesterId);
        $transaction = $payment['transaction'];
        $data = [
            'programme' => NTIService::getInfo('programmes', 'id', $applicant->programme_id)->abbr,
            'academic_session' => $academicSessionInfo->academicSession,
        ];

        if($request->is('applicants/download*'))
        {
            $html = view('applicants.print.invoice', 
                [
                    'applicant' => $applicant,
                    'transaction' => $transaction,
                    'data' => $data,
                    'pdf' => true
                ]
            )->render();
    
            $resp = PDFService::getPDF($html);

            ApplicantsModule::echoPDF($resp);

        }else
        {
            return view('applicants.print.invoice', 
                [
                    'applicant' => $applicant, 
                    'transaction' => $transaction,
                    'data' => $data,
                    //'collection' => $collection,
                ]
            );
        }
        
    }

    public function getReceipt(Request $request, $type = null)
    {
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $payment = ApplicantsModule::getTransaction($applicant, $type, $academicSemesterId);
        $transaction = $payment['transaction'];
        $data = [
            'programme' => NTIService::getInfo('programmes', 'id', $applicant->programme_id)->abbr,
            'academic_session' => $academicSessionInfo->academicSession,
        ];

        if($request->is('applicants/download/*'))
        {
            $html = view('applicants.print.receipt', 
            [
                'applicant' => $applicant,
                'transaction' => $transaction,
                'data' => $data,
                'pdf' => true
            ])->render();
        
            $resp = PDFService::getPDF($html);
        
            ApplicantsModule::echoPDF($resp);
            
            //file_put_contents(storage_path('app/public') . '/receipt/receipt.pdf', $resp);

            //return response()->download(storage_path('app/public') . '/receipt/receipt.pdf', 'mynti-receipt.pdf');

        }else
        {
            return view('applicants.print.receipt', 
                [
                    'applicant' => $applicant,
                    'transaction' => $transaction,
                    'data' => $data,
                ]
            );
        }
    }

    public function uploadFile()
	{
        $file = realpath('remita.png');
        $resp = PDFService::uploadAsset($file);
        dd($resp);
	}
}