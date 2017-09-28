<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Remita Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default values for communicating with the remita API
    |
    */

    'merchantId' => env('MERCHANTID'),

    'serviceTypeId' => env('SERVICETYPEID'),

    'apiKey' => env('APIKEY'),

    'checkstatusurl' => env('CHECKSTATUSURL'),

    'gatewayUrl' => env('GATEWAYURL'),

    'responseUrl' => env('RESPONSEURL'),

    'beneficiaryName1' => env('BENEFICIARYNAME1'),

    'beneficiaryName2' => env('BENEFICIARYNAME2'),
    
    'beneficiaryAccount1' => env('BENEFICIARYACCOUNT1'),

    'beneficiaryAccount2' => env('BENEFICIARYACCOUNT2'),

    'bankCode1' => env('BANKCODE1'),

    'bankCode2' => env('BANKCODE2')
];
