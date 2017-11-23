<?php

namespace NTI\Http\Controllers\Students;

use \NTI\Repository\Services\NTI as NTIServices;
use \NTI\Repository\Modules\StudentsModule;
use \NTI\Repository\Services\Remita;

use NTI\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeController extends Controller
{

    public $feeClass = "active";
    public $page = "Fees & Payments";

    protected $student;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:student');        

        $this->middleware(function($request, $next){
            $this->student = new StudentsModule(auth()->id());
            return $next($request);
        });

    }

    public function index()
    {

        //this function displays Outstanding Fees (tuition fee & fee assignments)
        
        $data = [
            "page" => $this->page,
            "feeClass" => $this->feeClass,
            "tab1" => "active",
            "sn" => 1,
            "studentInstance" => $this->student,
            "NTIServices" => "\NTI\Repository\Services\NTI",
            "outstandingTuitionFees" => $this->student->getOutstandingTuitionFees(), //Outstanding Tuition Fees
            "outstandingFeeAssignments" => $this->student->getOutstandingFeeAssignments() //Outstanding Fee Assignments
        ];

        return view('students.fees.index', $data);

    }

    public function history()
    {
      //this function displays Other Fees view
        
        $data = [
            "page" => $this->page,
            "feeClass" => $this->feeClass,
            "tab2" => "active",
            "studentInstance" => $this->student
        ];

        return view('students.fees.history', $data);

    }

    public function historyTable($semesterId)
    {
        //this function displays the history table from Ajax request.
        $this->student->printPaymentHistroyTable($semesterId);
    }

    public function others()
    {
      //this function displays Other Fees view
        
        $data = [
            "page" => $this->page,
            "feeClass" => $this->feeClass,
            "tab3" => "active",
            "sn" => 1,
            "otherFees" => $this->student->getOtherFees(),
            "NTIServices" => "\NTI\Repository\Services\NTI"
        ];

        return view('students.fees.others', $data);

    }

    public function processPayment(Request $request)
    {
     
        //recieve POST requests
        $table = $request->tbl;
        $id = $request->id;
        $installment = $request->installment;
        $semesterId = $request->semesterId;

        //get the amount from the fee definition in the DB
        $fee = NTIServices::getInfo($table, 'id', $id);
        $feeId = $fee->fee_id;
        $amount = ($installment == 0) ? $fee->amount : @json_decode($fee->installment)->$installment;

        //check if the transaction exists
        $transactionId = $this->student->transactionExists($feeId, $semesterId, $installment, $table, $id);

        if($transactionId !== false)
        {
            $transactionId = encrypt($transactionId);
            return redirect("/students/fees/invoice/".$transactionId);
        }

        //get the student's info
        $payerName = $this->student->getFullname();
        $payerEmail = $this->student->getMyProfile()->email;
        $payerPhone = $this->student->getMyProfile()->phoneNo;
        $studentId = $this->student->getMyProfile()->studentId;

        //generate RRR for the transaction
        $serviceTypeId = NTIServices::getInfo('fee_types', 'id', $feeId)->remitaServiceTypeId;

        $remita = new Remita($serviceTypeId);
        $orderId = $remita->generateOrderId(auth()->id());
        $responseUrl = url('/students/payment/status');
        $hash1 = $remita->generateHash1($orderId, $amount, $responseUrl);
        $itemId1 = $feeId;
        $itemId2 = $feeId.$remita->timesammp();
        $splitDefinition = json_decode($fee->beneficiaries);
        $beneficiaryAmount1 = (isset($splitDefinition->nti)) ? $splitDefinition->nti : $amount;
        $beneficiaryAmount2 = (isset($splitDefinition->omniswift)) ? $splitDefinition->omniswift : 0;
        
        $remitaObject = [
			"totalAmount" => $amount,
			"hash1" => $hash1,
			"orderId" => $orderId,
			"responseUrl" => $responseUrl,
			"payerName" => $payerName,
			"payerEmail" => $payerEmail, 
			"payerPhone" => $payerPhone,
			"itemId1" => $itemId1,
			"itemId2" => $itemId2,
			"beneficiaryAmount1" => $beneficiaryAmount1,
			"beneficiaryAmount2" => $beneficiaryAmount2
        ];

        $remitaBefore = $remita->getRemitaResponse($remitaObject);  
        
        if(! (array_key_exists('RRR', (array)$remitaBefore)) )
        {
            $message = "Unable to generate RRR." . NTIServices::smiley('sad');
            $error = json_encode($remitaBefore);

            $fullname = @$this->student->getFullname();
            $myProfile = $this->student->getMyProfile();
            $regNo = @$myProfile->regNo;
            $recoveryEmail = @$myProfile->recoveryEmail;
            $phone = @$myProfile->phoneNo;
            
            //send email to support
            $infoBip = new \NTI\Repository\Services\InfoBip();
            
            $support = NTIServices::basicInfo()->supportEmail;        
            $subject = "Error Encountered While Generating RRR";
            $body = "Hi Support,\n\nFull name: {$fullname}\nRegNo: {$regNo}\nRecovery Email: {$recoveryEmail}\nPhone: {$phone}.\n\nBelow is the error encountered:\n{$error}\n\nThank you.";
            
            //if the remitaBefore is not NULL, send the error to Support.
            if($remitaBefore !== null)
                $infoBip->sendEmail($support, $subject, $body);

            //redirect to custom error handler
            $url = '/error'.'/'.encrypt($message).'/'.encrypt($error);

            return redirect($url);
        }
    
        //create transaction record
        $transaction = new \NTI\Models\Transaction;
        $transaction->fee_id = $feeId;
        $transaction->semester_id = $semesterId;
        $transaction->param = "student";
        $transaction->val = $studentId;
        $transaction->installment = $installment;
        $transaction->amount = $amount;
        $transaction->orderId = $orderId;
        $transaction->remitaBefore = json_encode($remitaBefore);
        $transaction->status = "unpaid";
        $transaction->fee_table = $table;
        $transaction->fee_table_id = $id;
        $transaction->save();

        $transactionId = encrypt($transaction->id);
        return redirect("/students/fees/invoice/".$transactionId);

    }

    public function paymentStatus(Request $request)
    {

        $RRR = @str_replace('-', '', $request->RRR);
        $studentId = $this->student->getMyProfile()->studentId;
        $data = [
            "page" => $this->page,
            "feeClass" => $this->feeClass,
            "status" => "404",
            "message" => "RRR NOT FOUND",
            "RRR" => $request->RRR,
        ];

        //get the transaction for that RRR
        $transaction = NTIServices::getRRRInfo($RRR);
        $RRRIsMine = @($transaction->param == 'student' && $transaction->val == $studentId) ? true : false;

        if($RRRIsMine)
        {
            //verify RRR
            $remita = new Remita();
            $remitaAfter = $remita->verifyPayment("RRR", $RRR);
            NTIServices::consoleLog(json_encode($remitaAfter));

            $encryptedTransactionId = @encrypt($transaction->id);            

            //update the transaction record
            $transaction = \NTI\Models\Transaction::find($transaction->id);
            $transaction->remitaAfter = json_encode($remitaAfter);        
            $transaction->status = @($remitaAfter->status == "01" || $remitaAfter->status == "00" ) ? "paid" : "unpaid";        
            $transaction->save();

            $data = [
                "page" => $this->page,
                "feeClass" => $this->feeClass,    
                "status" => @$remitaAfter->status,
                "message" => @$remitaAfter->message,
                "RRR" => $request->RRR,
                "encryptedTransactionId" => $encryptedTransactionId
            ];

        }

        return view('students.fees.paymentStatus', $data);

    }

    public function invoice($encryptedTransactionId)
    {

        $transactionId = decrypt($encryptedTransactionId);

        $data = [
            "myProfile" => $this->student->getMyProfile(),
            "fullname" => $this->student->getFullname(),
            "transaction" => NTIServices::getInfo('transactions', 'id', $transactionId),
            "NTIServices" => "\NTI\Repository\Services\NTI",
            "remitaInstance" => new Remita()
        ];

        return view('students.fees.invoice', $data);

      
    }

    public function receipt($encryptedTransactionId)
    {

        $transactionId = decrypt($encryptedTransactionId);

        $data = [
            "myProfile" => $this->student->getMyProfile(),
            "fullname" => $this->student->getFullname(),
            "transaction" => NTIServices::getInfo('transactions', 'id', $transactionId),
            "NTIServices" => "\NTI\Repository\Services\NTI",
            "remitaInstance" => new Remita()
        ];

        return view('students.fees.receipt', $data);

      
    }

    
}
