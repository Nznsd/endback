<?php
/**
 * Created by PhpStorm.
 * User: sadiq
 * Date: 8/6/2017
 * Time: 8:14 PM
 */

namespace NTI\Repository\Services;

use NTI\Repository\Services\NTI as NTIServices;


class InfoBip
{

    private $baseUrl = "https://api.infobip.com";
    private $username = "omniswift";
    private $password = "Idris12!";

    private $string;
    private $MULTIPART_BOUNDARY;
    private $EOL;
    
    public function __construct()
    {
        
        $this->string = base64_encode($this->username.":".$this->password);
        
        $this->MULTIPART_BOUNDARY = '-----------------------'.md5(time());
        $this->EOL = "\r\n"; // PHP_EOL cannot be used for emails we need the CRFL '\r\n'

    }

    public function sendSMS($to, $message, $from = "MYNTIPORTAL")
    {

        $to = NTIServices::phoneNoFormatter($to);

        $body = [
            "from" => $from,
            "to" => $to,
            "text" => $message
        ];

        $body = json_encode($body);

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://api.infobip.com/sms/1/text/single', [
           'headers' => [
                      'Authorization' => 'Basic '.$this->string,
                      'Content-Type' => 'application/json',
                      'Accept' => 'application/json'
                  ],
                  'body' => $body
        ]);

        return $response;

    }

    /*
     * Method to convert an associative array of parameters into the HTML body string
    */
    private function getBody($fields) {
        $content = '';
        foreach ($fields as $FORM_FIELD => $value) {
            $content .= '--' . $this->MULTIPART_BOUNDARY . $this->EOL;
            $content .= 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"' . $this->EOL;
            $content .= $this->EOL . $value . $this->EOL;
        }
        return $content . '--' . $this->MULTIPART_BOUNDARY . '--'; // Email body should end with "--"
    }
    
    /*
     * Method to get the headers for a basic authentication with username and passowrd
    */
    private function getHeader(){
        // Define the header
        return array("Authorization:Basic {$this->string}", 'Content-Type: multipart/form-data ; boundary=' . $this->MULTIPART_BOUNDARY );
    }
    

    public function sendEmail($to, $subject, $text, $from = "ntiportal@nti.edu.ng")
    {

        // URL to the API that sends the email.
        $url = 'https://api.infobip.com/email/1/send';

        // Associate Array of the post parameters to be sent to the API
        $postData = array(
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'text' => $text,
        );

        $header = $this->getHeader();
        $content = $this->getBody($postData);

    
        // Create the stream context.
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => $header,
                'content' => $content,
            )
        ));
    
        // Read the response using the Stream Context.
        $response = file_get_contents($url, false, $context);
        
        return $response;

    }
    
    
    
}