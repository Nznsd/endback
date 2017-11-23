<?php

use Illuminate\Http\Request;

use NTI\Repository\Services\NTI as NTIServices;


/*
|--------------------------------------------------------------------------
|AUTH ROUTES
|--------------------------------------------------------------------------
|
*/

Route::get('/login', function (Request $request) {
    return view('students.auth.login');
})->middleware('guest');

//Create Account
Route::get('/create', function(){
   return view('students.auth.create');     
})->middleware('guest');

Route::get('/validate', function(Request $request){

    //this route returns a student's record
     $data = @NTIServices::getInfo('students', 'reg_no', $request->regNo);

    if($request->format == 'true' && isset($data))
    {
        $data = @[
            "fullname" => strtoupper($data->surname).' '.ucwords($data->firstname).' '.ucwords($data->othername),
            "surname" => $data->surname,
            "firstname" => $data->firstname,
            "regNo" => strtoupper($data->reg_no),
            "programme" => NTIServices::getInfo('programmes', 'id', $data->programme_id)->name,
            "specialization" => NTIServices::getInfo('specializations', 'id', $data->specialization_id)->abbr,
            "entryYear" => $data->entry_year,
            "sor" => NTIServices::getInfo('states', 'id', $data->sor)->name,
            "studyCenter" => NTIServices::getInfo('study_centers', 'id', $data->study_center_id)->name
        ];
    }

    return response()->json($data);
});

//Confirm Registration Number
Route::get('/confirm', function(){

    $data = [
        "programmes" => NTIServices::getProgrammes(),
        "states" => NTIServices::getStates()
    ];

    return view('students.auth.confirm', $data);
});

Route::get('/getstudycenters/{programmeId}/{stateId}', function($programmeId, $stateId){

    //this function returns study centers <options>

    $studyCenters = NTIServices::getStudyCenters($stateId, $programmeId);

    foreach ($studyCenters as $value) {
        echo "<option value='{$value->id}'>{$value->name}</option>";
    }

});

Route::get('/confirm/{surname}/{programme}/{entryYear}/{state}/{studyCenter}', function($surname, $programme, $entryYear, $state, $studyCenter){
    
    //this function returns the data for the above params

    $data = \Illuminate\Support\Facades\DB::table('students')
            ->whereNull('user_id')
            ->where([
                ['surname', $surname],
                ['programme_id', $programme],
                ['entry_year', $entryYear],
                ['sor', $state],
                ['study_center_id', $studyCenter]
            ])
            ->first();

    if(!empty($data))      
    {
        echo json_encode([
            "msg" => "Your registartion number is: {$data->reg_no}",
            "regNo" => $data->reg_no
        ]);
    }  

});

/*
|--------------------------------------------------------------------------
|DASHBOARD ROUTES
|--------------------------------------------------------------------------
|
*/

Route::get('/', function(){
    return redirect('/students/fees');
});


/*
|--------------------------------------------------------------------------
|PROFILE ROUTES
|--------------------------------------------------------------------------
|
*/

Route::get('/profile', 'Students\ProfileController@index');
Route::post('/profile/update', 'Students\ProfileController@update');


/*
|--------------------------------------------------------------------------
|FEES ROUTES
|--------------------------------------------------------------------------
|
*/

Route::get('/fees', 'Students\FeeController@index');
Route::get('/fees/history', 'Students\FeeController@history');
Route::get('/fees/history/{semesterId}', 'Students\FeeController@historyTable');
Route::get('/fees/others', 'Students\FeeController@others');

Route::post('/fees/processpayment', 'Students\FeeController@processPayment');
Route::get('/payment/status', 'Students\FeeController@paymentStatus');

Route::get('/fees/invoice/{transactionId}', 'Students\FeeController@invoice');
Route::get('/fees/receipt/{transactionId}', 'Students\FeeController@receipt');

/*
|--------------------------------------------------------------------------
|COURSE REGISTRATION ROUTES
|--------------------------------------------------------------------------
|
*/
Route::get('/courses', 'Students\CourseController@index');
Route::get('/courses/carryover', 'Students\CourseController@carryover');
Route::get('/courses/history', 'Students\CourseController@history');
Route::get('/courses/materials', 'Students\CourseController@materials');

Route::post('/courses/register', 'Students\CourseController@register');

Route::get('/course/form/{courseRegistrationId}', 'Students\CourseController@form');

/*
|--------------------------------------------------------------------------
|CA, EXAMS ROUTES
|--------------------------------------------------------------------------
|
*/


/*
|--------------------------------------------------------------------------
|OTHER SERVICES ROUTES
|--------------------------------------------------------------------------
|
*/


