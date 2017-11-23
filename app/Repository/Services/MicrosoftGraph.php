<?php
/**
 * Created by PhpStorm.
 * User: sadiq
 * Date: 8/6/2017
 * Time: 8:14 PM
 */

namespace NTI\Repository\Services;

use Illuminate\Http\Request;


class MicrosoftGraph
{

    public $clientId = 'a70ae4f8-b149-46e2-8dcc-17507bddc504';
    public $clientSecret = 'e6Xpd3k8fijCLDkAzocerjs';
    public $redirectUri;
    public $urlAuthorize = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';
    public $urlAccessToken = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
    public $urlResourceOwnerDetails = '';
    public $scopes = 'openid mail.send Mail.Read Mail.ReadWrite User.ReadBasic.All User.Read';

    public $domain = 'nti.edu.ng';
    public $adminEmail = 'dev@nti.edu.ng'; 
    public $tenantId = 'ccb1c17b-b456-4854-aa9c-514b6a460aa0';

    public function __construct()
    {
        $this->redirectUri = env('APP_URL').'/oauth';
    }

    /*
    |--------------------------------------------------------------------------
    | DELEGATED PERMISSIONS
    |--------------------------------------------------------------------------
    |
    */


    /*
    |--------------------------------------------------------------------------
    | APPLICATION PERMISSIONS
    |--------------------------------------------------------------------------
    |
    */

    public function sendEmail($to, $subject, $content)
    {
        //send email to a user
        
        $domain = $this->domain;
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://login.microsoftonline.com/'.$domain.'/oauth2/v2.0/token', [
           'headers' => [
               'Content-Type' => 'application/x-www-form-urlencoded'
           ],
           'form_params' => [
                      'client_id' => $clientId,
                      'scope' => 'https://graph.microsoft.com/.default',
                      'client_secret' => $clientSecret,
                      'grant_type' => 'client_credentials'
            ]
        ]);

      $jsonResponse = json_decode($response->getBody());
 
        $email = '{
            Message: {
            Subject: "'.$subject.'",
            Body: {
                ContentType: "text",
                Content: "'.$content.'"
            },
            ToRecipients: [
                {
                    EmailAddress: {
                    Address: "'.$to.'"
                    }
                }
            ]
            }}';
        
        $response = $client->request('POST', 'https://graph.microsoft.com/v1.0/me/sendMail', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jsonResponse->access_token,
                'Content-Type' => 'application/json'
            ],
            'body' => $email
        ]);

        return $response.getStatusCode(); 

    }

    public function listUsers()
    {
        //returns the list of microsoft users

        $domain = $this->domain;
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://login.microsoftonline.com/'.$domain.'/oauth2/v2.0/token', [
           'headers' => [
               'Content-Type' => 'application/x-www-form-urlencoded'
           ],
           'form_params' => [
                      'client_id' => $clientId,
                      'scope' => 'https://graph.microsoft.com/.default',
                      'client_secret' => $clientSecret,
                      'grant_type' => 'client_credentials'
            ]
        ]);

        $jsonResponse = json_decode($response->getBody());

        $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/users', [
            'headers' => [
                'Authorization' => 'Bearer '.$jsonResponse->access_token
            ]
        ]);

        return json_decode($response->getBody());

    }

    public function createUser($surname, $firstName, $regNo, $email, $password)
    {

        $domain = $this->domain;
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;

        $displayName = ucwords($surname)." ".ucwords($firstName);
        $mailNickname = strtoupper($regNo);

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://login.microsoftonline.com/'.$domain.'/oauth2/v2.0/token', [
           'headers' => [
               'Content-Type' => 'application/x-www-form-urlencoded'
           ],
           'form_params' => [
                      'client_id' => $clientId,
                      'scope' => 'https://graph.microsoft.com/.default',
                      'client_secret' => $clientSecret,
                      'grant_type' => 'client_credentials'
            ]
        ]);

      $jsonResponse = json_decode($response->getBody());

      $body = '{
                "accountEnabled": true,
                "displayName": "'.$displayName.'",
                "mailNickname": "'.$mailNickname.'",
                "userPrincipalName": "'.$email.'",
                "passwordProfile" : {
                                    "forceChangePasswordNextSignIn": false,
                                    "password": "'.$password.'"
                                    }
                }';

      $response = $client->request('POST', 'https://graph.microsoft.com/v1.0/users', [
                  'headers' => [
                      'Authorization' => 'Bearer ' . $jsonResponse->access_token,
                      'Content-Type' => 'application/json'
                  ],
                  'body' => $body
              ]);

      return $response;          

    }

    public function deleteUser($email)
    {
        //deletes the specified email

        $domain = $this->domain;
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://login.microsoftonline.com/'.$domain.'/oauth2/v2.0/token', [
           'headers' => [
               'Content-Type' => 'application/x-www-form-urlencoded'
           ],
           'form_params' => [
                      'client_id' => $clientId,
                      'scope' => 'https://graph.microsoft.com/.default',
                      'client_secret' => $clientSecret,
                      'grant_type' => 'client_credentials'
            ]
        ]);

        $jsonResponse = json_decode($response->getBody());

        $response = $client->request('DELETE', 'https://graph.microsoft.com/v1.0/users/'.$email, [
            'headers' => [
                'Authorization' => 'Bearer '.$jsonResponse->access_token
            ]
        ]);

        return json_decode($response->getBody());

    }

}