<?php

use Illuminate\Http\Request;
use NTI\Repository\Services\MicrosoftGraph;
use NTI\Repository\Services\NTI as NTIServices;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/blem', function(){
    
    $message = encrypt("BLEM! &#9786;");
    $error = encrypt("I'm blem for real, I might just say how i feel...");

    return redirect("/error/{$message}/{$error}");
    
});

/*
|--------------------------------------------------------------------------
|GENERAL ROUTES
|--------------------------------------------------------------------------
|
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/support', function() {
    return view("support");
});

/*
|--------------------------------------------------------------------------
|MANUAL GUIDE ROUTES
|--------------------------------------------------------------------------
|
|
*/

Route::get('/registration-guide', function() {
    
});

Route::get('/registration-guide/{version}/en/topic/{subject?}', function() {
    
});

Route::get('/application-guide', function() {
    
});

Route::get('/application-guide/{version}/en/topic/{subject?}', function() {
    
});

/*
|--------------------------------------------------------------------------
|AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
|
|
*/
Auth::routes();

Route::get('/home', function(){

    echo "<p>You'll be redirected in a moment...</p>";
    
    $userId = \NTI\Models\User::find(auth()->id());
    $redirect = ($userId->role == 'admin') ? '/admin' : '/'.$userId->role.'s';

    return redirect($redirect);

})->name('home');


// Route::get('/password/confirm-rrr/{eid?}', 'Auth\ForgotPasswordController@getRRR');

// Route::post('/password/confirm-rrr', 'Auth\ForgotPasswordController@confirmRRR');

Route::post('/password/change', 'Auth\ForgotPasswordController@change');

/*
|--------------------------------------------------------------------------
|MICROSOFT GRAPH ROUTES
|--------------------------------------------------------------------------
|
*/

//Sign in with Microsoft 
Route::get('/oauth', function (Request $request) {

    $graph = new MicrosoftGraph;

    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => $graph->clientId,
        'clientSecret'            => $graph->clientSecret,
        'redirectUri'             => $graph->redirectUri,
        'urlAuthorize'            => $graph->urlAuthorize,
        'urlAccessToken'          => $graph->urlAccessToken,
        'urlResourceOwnerDetails' => $graph->urlResourceOwnerDetails,
        'scopes'                  => $graph->scopes
    ]);

    if (!$request->has('code')) {
        return redirect($provider->getAuthorizationUrl());
    }
    else {
        
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code'     => $request->input('code')
        ]);

        //Get the authentciated user's profile from Microsoft
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken->getToken(),
                'Content-Type' => 'application/json;odata.metadata=minimal;odata.streaming=true'
            ]
        ]);

        $body = json_decode($response->getBody());
        $email = $body->userPrincipalName;

        //retrieve the user id from our database using email
        $user = NTI\Models\User::where('email', $email)->first();   

        if(empty($user))
            return redirect('students/login')->with("status", "Sorry, we don't recognize your login credential. Kindly contact support@omniswift.com");
        
            //login the user by id
        Auth::loginUsingId($user->id);
        $redirect = ($user->role == 'admin') ? '/admin' : '/'.$user->role.'s';            
            
        return redirect($redirect);

    }

})->middleware('guest');    

//Create Microsoft Account
Route::post('/create', function(Request $request){

    //get requests
    $surname = @$request->surname;
    $firstname = @$request->firstname;
    $regNo = @$request->regNo;
    $recoveryEmail = @$request->recoveryEmail;
    $phone = @$request->phone;
    $updatesArray = [
        "recovery_email" => $recoveryEmail,
        "phone" => $phone
    ];

    //if user_id exists for the student, redirect to credential invoice
    
    $userId = @NTIServices::getInfo("students", "reg_no", $regNo)->user_id;
    
    if($userId !== null){
        $encryptUserId = base64_encode($userId);        
        $email = @base64_encode(NTIServices::getInfo('users', 'id', $userId)->email);
        return redirect("/credential/{$encryptUserId}/{$email}");
    }

    //update the student's data. 
    NTIServices::updateRecord('students', 'reg_no', $regNo, $updatesArray);

    //generate custom email
    $email = NTIServices::generateEmail($surname, $firstname);

    //generate password
    $password = NTIServices::generatePassword();

    //create account on Microsoft (no need to handle response code. because if it fails, the whole system breaks-down automatically)
    try{
    $graph = new MicrosoftGraph();
    $response = $graph->createUser($surname, $firstname, $regNo, $email, $password);
    }
    catch(Exception $e){
        $message = "Unable to create your account." .NTIServices::smiley('sad');
        $error = json_encode($e->getMessage());

        //send email to support
        $fullname = strtoupper($surname). " ".ucwords($firstname);

        $infoBip = new \NTI\Repository\Services\InfoBip();
        
        $support = NTIServices::basicInfo()->supportEmail;        
        $subject = "Error Encountered While Creating Office 356 Account";
        $body = "Hi Support,\n\nFull name: {$fullname}\nRegNo: {$regNo}\nRecovery Email: {$recoveryEmail}\nPhone: {$phone}.\n\nBelow is the error encountered:\n{$error}\n\nThank you.";

        $infoBip->sendEmail($support, $subject, $body);

        //redirect to error page
        $url = '/error'.'/'.encrypt($message).'/'.encrypt($error);
        return redirect($url);
    }

    //create account on Users table

    $user = new \NTI\Models\User;
    $user->email = $email;
    $user->password = bcrypt($password);
    $user->role = "student";
    $user->save();

    $userId = $user->id;
    $encryptUserId = base64_encode($userId);

    //update record on Students table
    if(NTIServices::updateRecord('students', 'reg_no', $regNo, ["user_id" => $userId]))
    {
        $infoBip = new \NTI\Repository\Services\InfoBip();

        //send SMS
        $text = "Hi. Your Email is: {$email}. Password: {$password}. Thank you!";
        $infoBip->sendSMS($phone, $text);

        //send Email
        $fullname = strtoupper($surname). " ".ucwords($firstname);
        $url = env('APP_URL')."/credential"."/".$encryptUserId."/".base64_encode($email)."/".base64_encode($password);

        $subject = "NTI Portal Login Credential";
        $body = "Hi {$fullname},\n\nYour Email is: {$email}. Password: {$password}.\nPrint a copy of your login credential here: {$url}\n\nThank you.";

        $infoBip->sendEmail($recoveryEmail, $subject, $body);

    }

    //redirect to credential invoice
    $email = base64_encode($email);
    $password = base64_encode($password);

    return redirect("/credential/{$encryptUserId}/{$email}/{$password}");

});

//DANGER ZONE: Delete Microsoft Account
Route::get('/delete/{email}', function($email){
    try{
        $graph = new MicrosoftGraph();
        $response = $graph->deleteUser($email);
    }
    catch(Exception $e){
        dd($e);
    }

});

//Credential Printout
Route::get('/credential/{userId}/{email}/{password?}', function($userId, $email, $password = null){

    $userId = base64_decode($userId);
    $email = base64_decode($email);
    $password = base64_decode($password);

    $student = new \NTI\Repository\Modules\StudentsModule($userId);
    $fullname = $student->getFullname();
    $myProfile = $student->getMyProfile();
    $NTIServices = 'NTI\Repository\Services\NTI';

    return view('students.auth.credential', compact('fullname', 'myProfile','email', 'password', 'NTIServices'));
});

//Error Handler Route
Route::get('/error/{message}/{error}', function($message, $error){

    $message = decrypt($message);
    $error = decrypt($error);

    return view('errors.custom', compact('message', 'error'));
});