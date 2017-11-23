<?php
/*
|--------------------------------------------------------------------------
| NTI Services Routes/Endpoints
|--------------------------------------------------------------------------
*/

use Illuminate\Http\Request;
use NTI\Repository\Services\NTI;
use NTI\Models\Grade;


/*
    |--------------------------------------------------------------------------
    | PROGRAMMES, SPECIALIZATIONS AND LEVEL RELATED ENDPOINTS
    |--------------------------------------------------------------------------
    |
    */
Route::get('/programmes', function() {
    //return 'this service endpoint returns all programmes';
    $programmes = NTI::getProgrammes();

    return response()
        ->json($programmes);
});

Route::get('/specializations/{id}', function($id) {
    //return 'this service endpoint returns specializations for programe: ' . $id;
    $specializations = NTI::getSpecializations($id);

    return response()
        ->json($specializations);
});

Route::get('levels/{programme_id}', function($programme_id) {
    $levels = NTI::getLevels($programme_id);

    return response()
        ->json($levels);
});

 /*
    |--------------------------------------------------------------------------
    | STUDY CENTERS RELATED ENDPOINTS
    |--------------------------------------------------------------------------
    |
*/
Route::get('/centers/{state_id}/{programme_id}', function($state_id, $programme_id) {
    // return 'this service endpoint returns all centers for state: ' . $id;
    if($programme_id == 4) // if programme is 4(ADE)
        $programme_id = 3; // change it to 3(PGDE) as they share same study centers
    $centers = NTI::getStudyCenters($state_id, $programme_id);

    return response()
        ->json($centers);
});

/*
    |--------------------------------------------------------------------------
    | COURSES RELATED SERVICES
    |--------------------------------------------------------------------------
    |
*/
Route::get('courses/{programme_id}/{specializationId}/{level}/{semester}', function($programme_id, $specialization_id, $level, $semester){
    $courses = NTI::getCourses($programme_id, $specialization_id, $level, $semester);

    return response()
        ->json($courses);
});

/*
    |--------------------------------------------------------------------------
    | FEES RELATED SERVICES
    |--------------------------------------------------------------------------
    |
*/
Route::get('fee-types', function(){
    $fees = NTI::getFeeTypes();

    return response()
        ->json($fees);
});

Route::get('fee/{feeId}/{programmeId}/{specializationId}/{level?}/{semester?}/{category?}', function($feeId, $programmeId, $specializationId, $level = 1, $semester = 1, $category = 'default') {
    // return 'this service endpoint returns application fee for given fee & programme id    
    $fee_def = NTI::getFeeDefinition($feeId, $programmeId, $specializationId, $level, $semester, $category);

    return response()
        ->json($fee_def);
});

/*
    |--------------------------------------------------------------------------
    | APPLICANTS RELATED ENDPOINTS
    |--------------------------------------------------------------------------
    |
 */
Route::get('/subjects', function() {
   //  return 'this service endpoint returns all subjects for WAEC etc';
   $subjects = NTI::getSubjects();

   return response()
    ->json($subjects);
});

Route::get('/grades/{cert_id}/{type_id}', function($cert_id, $type_id) {
    // return 'this service endpoint returns grades for subjects: A, B, C etc';
    if($cert_id == "1" )
    {
        if($type_id == 'gce')
        {
            $type_id = 4;
        } else {
            $type_id = 1;
        }  
    } else if($cert_id == "2")
    {
        if($type_id == 'degree')
        {
            $type_id = 1;
        } else if($type_id == 'coe'){
            $type_id = 2;
        } else if($type_id == 'tcii'){
            $type_id = 3;
        } else if($type_id == 'poly'){
            $type_id = 5;
        } else if($type_id == 'pttp'){
            $type_id = 5;
        }     
    }
    $grades = Grade::where([
                ['certificate_id', $cert_id],
                ['type_id', $type_id],
            ])->get();

    return response()
        ->json($grades);
});

/*
    |--------------------------------------------------------------------------
    | STUDENTS RELATED SERVICES
    |--------------------------------------------------------------------------
    |
*/

 /*
    |--------------------------------------------------------------------------
    | GENERAL SERVICES ENDPOINTS
    |--------------------------------------------------------------------------
    |
*/

Route::get('/states', function($zone_id = null) {
    // return 'this service endpoint returns all states in Nigeria with their ID';
    $states = NTI::getStates($zone_id);

    return response()
        ->json($states);
});


Route::get('/lga/{state_id}', function($state_id) {
    // return 'this service endpoint returns all LGAs for state: ' . $id;
    $lgas = NTI::getLGA($state_id);
    
    return response()
        ->json($lgas);
});

Route::get('zones', function() {
    $zones = NTI::getZones();

    return response()
        ->json($zones);
});

