<?php

namespace NTI\Http\Controllers\Applicants;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use NTI\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

use NTI\Models\Applicant;
use NTI\Models\Programme;
use NTI\Models\Specialization;
use NTI\Models\FeeDefinition;
use NTI\Models\Transaction;
use NTI\Repository\Services\NTI as NTIService;
use NTI\Repository\Services\Remita as RemitaService;


class ApplicantPaymentController extends Controller
{
    protected $academicSessionId;
    protected $academicSemesterId;

    public function __construct()
    {
       $this->middleware(['auth', 'role:applicant']);
       $this->middleware('payment')->only('pay');
    }

    public static function getFeeDefParams($applicant, $type)
    {
        $params['fee_id'] = $type == 'tuition' ? 19 : 2;
        $params['specialization_id'] = $type == 'tuition' ? $applicant->first_choice : 0;
        $params['level'] = $type == 'tuition' &&
         ($applicant->programme_id == 1 || $applicant->programme_id == 5) 
         ? 1 : 0;
        $params['semester'] = 1;
        $params['category'] = $type == 'tuition' && $applicant->entry_level != 1 ? 'DE' : 'fresh';

        return $params;
    }

    public function testParams($type, $programme_id)
    {
        $applicant = json_decode(json_encode([
            'programme_id' => $programme_id,
            'first_choice' => 0,
            'entry_level' => 1,
        ]));
        $params = self::getFeeDefParams($applicant, $type);
        $fee_table = NTIService::getFeeDefinition($params['fee_id'], 
        $applicant->programme_id, $params['specialization_id'], 
        $params['level'], $params['semester'], $params['category']);
        dd($fee_table);
    }

    public function pay(Request $request, $type = null)
    {
        // set up required variables
        $appstate = 3;
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $params = self::getFeeDefParams($applicant, $type);
        $transaction = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => $params['fee_id'],          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();

        $fee_table_id = NTIService::getFeeDefinition($params['fee_id'], 
            $applicant->programme_id, $params['specialization_id'], 
            $params['level'], $params['semester'], $params['category'])->id;

         // construct payment parameters
         $rem = new RemitaService;
         $orderId = $rem->generateOrderId($applicant->id);
         $responseUrl = $rem->getResponseUrl('/applicants/remita-response/' . $type);
         $totalAmount = FeeDefinition::find($fee_table_id)->amount;
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
            if(!isset($response))
            {
                return redirect('applicants/programme')->withInput()
                    ->with('status', 'We are unable to contact Remita at the moment, please try again after a few seconds...');
            }

            $transaction = new Transaction;
            $transaction->fee_id = $params['fee_id'];
            $transaction->param = 'applicant';
            $transaction->val = $applicant->id;
            $transaction->amount = $totalAmount;
            $transaction->order_id = $orderId;
            $transaction->fee_table = 'fee_definitions';
            $transaction->fee_table_id = $fee_table_id;
            $transaction->semester_id = $academicSemesterId;
            $transaction->remita_before = json_encode($response);
            $transaction->save();
        } else if(!isset($transaction->remita_after) 
            || $transaction->status !== 'paid')
        {
            $paymentDetails["orderId"] = $transaction->order_id;
            $paymentDetails["totalAmount"] = $transaction->amount;
            // $paymentDetails["hash1"] = $rem->generateHash1($transaction->order_id, $transaction->amount, $responseUrl);
            $response = json_decode($transaction->remita_before);
        }


        $applicant->application_state = $applicant->application_state < $appstate ? $appstate : $applicant->application_state;
        $applicant->save();

        $programme = Programme::findOrFail($applicant->programme_id)->name;
        $specialization = Specialization::findOrFail($applicant->first_choice)->name;
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
        $transaction = Transaction::where('order_id', $request->orderID)->first();
        // construct json of remita payload
        $remita = [
            'orderId' => $request->orderID,
            'RRR' => $request->RRR
        ];

        $transaction->remita_after = json_encode($remita);
        $transaction->save();

        return redirect('applicants/verify/' . $type);
    }

    public function verifyGet(Request $request, $type = null)
    {
        $appstate = 4;
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo(); 
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $params = self::getFeeDefParams($applicant, $type);
        $transaction = Transaction::where([
            ['param','applicant'],
            ['val', $applicant->id],
            ['fee_id', $params['fee_id']],
            ['semester_id', $academicSemesterId]
                ])->first();
        if(!isset($transaction))
        {
            return redirect('applicants/programme');
        }

        if($transaction->status == 'unpaid')
        {
            $rem = new RemitaService;
            $rrr = json_decode($transaction->remita_before)->RRR;
            $response = $rem->verifyPayment('RRR', $rrr);
            $transaction->remita_after = isset($response) ? json_encode($response) : null;

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
            'programme' => NTIService::getInfo('programmes', $applicant->programme_id)->name,
            'specialization' => NTIService::getInfo('specializations', $applicant->first_choice)->name,
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
            ['remita_before->RRR', $request->rrr],
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
        
        if($transaction->status == 'unpaid')
        {
            $rem = new RemitaService;
            $rrr = $request->rrr;
            $response = $rem->verifyPayment('RRR', $rrr);
            $transaction->remita_after = isset($response) ? json_encode($response) : null;
    
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
            'programme' => NTIService::getInfo('programmes', $app->programme_id)->name,
            'specialization' => NTIService::getInfo('specializations', $app->first_choice)->name,
            'paystatus' => $transaction->status,
            'amount' => $transaction->amount,
            'rrr' => json_decode($transaction->remita_after)->RRR,
            'orderid' => $transaction->order_id,
            'message' => json_decode($transaction->remita_after)->message,
            'status' => json_decode($transaction->remita_after)->status,
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
        $params = self::getFeeDefParams($applicant, $type);
        $fee_def = NTIService::getFeeDefinition($params['fee_id'], 
                $applicant->programme_id, $params['specialization_id'], 
                $params['level'], $params['semester'], $params['category']);
        $collection = json_decode($fee_def->collection);
        $transaction = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => $params['fee_id'],          // make sure it is the transaction for admission or tuition fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();

        return view('applicants.print.invoice', 
        [
            'applicant' => $applicant, 
            'transaction' => $transaction,
            'collection' => $collection,
        ]);
    }

    public function getReceipt(Request $request, $type = null)
    {
        $academicSessionInfo = NTIService::getCurrentAcademicSessionInfo();
        $academicSemesterId = $academicSessionInfo->semesterId;
        $applicant = Applicant::where('user_id', $request->user()->id)->first();
        $params = self::getFeeDefParams($applicant, $type);
        $fee_def = NTIService::getFeeDefinition($params['fee_id'], 
                $applicant->programme_id, $params['specialization_id'], 
                $params['level'], $params['semester'], $params['category']);
        $collection = json_decode($fee_def->collection);
        $transaction = Transaction::where([
            'param' => 'applicant', // make sure its an applicant
            'val' => $applicant->id,// make sure its the logged in applicant
            'fee_id' => $params['fee_id'],          // make sure it is the transaction for admission form fee
            'semester_id' => $academicSemesterId // for current semester
        ])->first();

        return view('applicants.print.receipt', 
            [
                'transaction' => $transaction,
                'collection' => $collection,
            ]);
    }
}