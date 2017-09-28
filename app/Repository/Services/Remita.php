<?php

/*
*   AUTHOR: LUKMAN SADIQ
*   CREATED AT: MONDAY, JULY ‎31, ‎2017, ‏‎11:47:48 AM
*	DESCRIPTION: This is the Remita Service class.
*/

namespace NTI\Repository\Services;

class Remita{

	public $merchantId;
	public $gatewayRRRPaymentUrl;
	public $path;
	
	private $serviceTypeId;
	private $apiKey;
	private $gatewayUrl;
	private $checkStatusUrl;

	protected $beneficiaryName1;
	protected $beneficiaryName2;
	protected $beneficiaryAccount1;
	protected $beneficiaryAccount2;
	protected $bankCode1;
	protected $bankCode2;
	protected $deductFeeFrom1=1;
	protected $deductFeeFrom2=0;

	public function __construct()
	{

            $this->merchantId = "2547916";
            $this->serviceTypeId = "4430731";
            $this->apiKey = "1946";
            $this->gatewayUrl = "http://remitademo.net/remita/ecomm/v2/init.reg";
            $this->gatewayRRRPaymentUrl = "http://remitademo.net/remita/ecomm/finalize.reg";
            $this->checkStatusUrl = "http://remitademo.net/remita/ecomm";

            $this->beneficiaryName1 = "Oshadami Mke";
            $this->beneficiaryName2 = "Mujib Ishola";
            $this->beneficiaryAccount1 = "6020067886";
            $this->beneficiaryAccount2 = "0360883515";
            $this->bankCode1 = "011";
            $this->bankCode2 = "050";

            $this->path = env('APP_URL');

	}
	
	public function timesammp()
	{
		return DATE("dmyHis");
	}

	public function generateOrderId($authUser)
	{
		//returns a unique orderId everytime
        $orderId = $this->timesammp().$authUser;

		return $orderId;
	}

	public function getResponseUrl($page)
	{
		//this function returns the url that remita sends the response.
		$responseUrl = $this->path.$page;

		return $responseUrl;
	}

	public function generateHash1($orderId, $totalAmount, $responseUrl)
	{
		//this function returns the first hash you send to remita to getRemitaResponse
		$hashString = $this->merchantId.$this->serviceTypeId.$orderId.$totalAmount.$responseUrl.$this->apiKey;
		$hash = hash('sha512', $hashString);

		return $hash;
	}

	public function generateHash2($RRR)
	{
		//this function returns the second hash you send to remita to make payment via RRR
		$rrr = trim($RRR);
		$hashString = $this->merchantId.$rrr.$this->apiKey;
		$hash = hash('sha512', $hashString);

		return $hash;
	}

	private function createJSONData($array)
	{
		//this function creates the JSON object to be sent to Remita via CURL. Below are the params to expect from the array:

		/*Keys => [
			"totalAmount",
			"hash1",
			"orderId",
			"responseUrl",
			"payerName",
			"payerEmail", 
			"payerPhone",
			"itemId1",
			"itemId2",
			"beneficiaryAmount1",
			"beneficiaryAmount2"
		]*/

		$itemId1 = $array['itemId1'];
		$itemId2 = $array['itemId2'].$this->timesammp();

		$content = '{"merchantId":"'. $this->merchantId
					.'"'.',"serviceTypeId":"'.$this->serviceTypeId
					.'"'.",".'"totalAmount":"'.$array['totalAmount']
					.'","hash":"'. $array['hash1']
					.'"'.',"orderId":"'.$array['orderId']
					.'"'.",".'"responseurl":"'.$array['responseUrl']
					.'","payerName":"'. $array['payerName']
					.'"'.',"payerEmail":"'.$array['payerEmail']
					.'"'.",".'"payerPhone":"'.$array['payerPhone']
					.'","lineItems":[
					{"lineItemsId":"'.$itemId1.'","beneficiaryName":"'.$this->beneficiaryName1.'","beneficiaryAccount":"'.$this->beneficiaryAccount1.'","bankCode":"'.$this->bankCode1.'","beneficiaryAmount":"'.$array['beneficiaryAmount1'].'","deductFeeFrom":"'.$this->deductFeeFrom1.'"},
					{"lineItemsId":"'.$itemId2.'","beneficiaryName":"'.$this->beneficiaryName2.'","beneficiaryAccount":"'.$this->beneficiaryAccount2.'","bankCode":"'.$this->bankCode2.'","beneficiaryAmount":"'.$array['beneficiaryAmount2'].'","deductFeeFrom":"'.$this->deductFeeFrom2.'"}
					]}';

		return $content;					
	}

	public function getRemitaResponse($paymentDetails)
	{
		//this function returns response from Remita. Below are params from the $paymentDetails array

			/*Keys => [
			"totalAmount",
			"hash1",
			"orderId",
			"responseUrl",
			"payerName",
			"payerEmail", 
			"payerPhone",
			"itemId1",
			"itemId2",
			"beneficiaryAmount1",
			"beneficiaryAmount2"
		]*/

		$content = $this->createJSONData($paymentDetails);

		$curl = curl_init($this->gatewayUrl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
		array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		$jsonData = substr($json_response, 6, -1);
		$response = json_decode($jsonData, true);

		return json_decode(json_encode($response));

	}

	public function verifyPayment($key, $value)
	{
        $merchantId =  $this->merchantId;
        $apiKey =  $this->apiKey;
        $hashString = $value . $apiKey . $merchantId;
		$hash = hash('sha512', $hashString);

		if($key === 'RRR')
			$endpoint = 'status.reg';
		else
			$endpoint = 'orderstatus.reg';

        $url 	= $this->checkStatusUrl . '/' . $merchantId  . '/' . $value . '/' . $hash . '/' . $endpoint;
        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);
        $response = json_decode($result, true);
        return json_decode(json_encode($response));
	}

}

