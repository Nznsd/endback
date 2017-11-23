<?php

/*
*   AUTHOR: LUKMAN SADIQ
*   CREATED AT: MONDAY, JULY ‎31, ‎2017, ‏‎11:47:48 AM
*	DESCRIPTION: This is the NTI Service class. Functions that would be used in all the modules are defined here. 
*/

namespace NTI\Repository\Services;

use Illuminate\Support\Facades\DB;

class NTI{
	
    /*
    |--------------------------------------------------------------------------
    | GENERAL SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function arrayToJson($collection)
    {
        return json_decode(json_encode($collection));
    }

    public static function jsonToArray($json)
    {
        //this function typecast json into array and make it iterable with forloop
        $array = (array) json_decode($json);
        return $array;
    }

    public static function basicInfo()
    {
        //this function contains basic info about the school. DO NOT ALTER ANYTHING
        $info = [
            "schoolName" => "National Teachers' Institute",
            "supportEmail" => "support@omniswift.com",
            "schoolAbbr" => "NTI", //useful for Registartion No. e.g: NTI/NCE/2017/001
            "schoolMoto" => "",
            "schoolLogo" => "",
            "schoolAddress" => "",
            "schoolDescription" => "",
            "domainExtention" => "nti.edu.ng",
            "nceId" => 1, //useful in Students->getMyLevelIn()
            "bdpId" => 5,
            "pttpId" => 2,
            "commissionedSemesterId" => 21 //Set the semesterId this portal went live. Useful in Students->getMySemesterIds()
        ];

        return static::arrayToJson($info);
    }

    public static function zeroFill($digit, $minLength = 3)
    {
        //this function prepends zeros to $digit.
        $digit = (string) $digit;
        $digitLength = strlen($digit);

        if($digitLength >= $minLength)
            return (string) $digit;

        $noOfZerosToAppend = $minLength - $digitLength;
        
        $array = [];

        for($i = 0; $i < $noOfZerosToAppend; $i++)
            $array[] = "0";            
            
        $zeroFill = implode('',$array);
        $zeroFilled = "{$zeroFill}{$digit}";

        return (string) $zeroFilled;

    }

    public static function numberToPosition($number)
    {
        //this function converts a number into poistion (st, nd, rd, th..)
        
        $int = (int) $number; 
        
        switch ($int) {
            case 1:
                return 'First';
                break;
            case 2:
                return 'Second';
                break;
            case 3:
                return 'Third';
                break;        
            default:
                return 'th';
                break;
        }
    }

    public static function smiley($face = null)
    {
        switch ($face) {
            case 'happy':
                return '&#9786;';
                break;
            case 'sad':
                return '&#9785;';
                break;
            default:
                return '';
                break;
        }
    }

    public static function responseMessage($status = null)
    {
        switch ($status) {
            case 'a':
                return "Hang tight! You'll be redirected in a moment...".static::smiley('happy');
                break;
            case 'b':
                return "Creating an account might take a while. Please be patient...".static::smiley('happy');
                break;
            case 'c':
                return "Aww! We are unable to create your account at this moment. Please try again...".static::smiley('sad');
                break;    
            case 'd':
                return "Unable to update record. Please try again...".static::smiley('sad');
                break;                
            default:
                return "Hi there! This will only take a sec...".static::smiley('happy');
                break;
        }
    }

    public static function consoleLog($message)
    {
        echo"<script>console.log('".$message."')</script>";
    }

    public static function getInfo($table, $column, $value)
    {
        //returns information about a record. Works like eloquent find()
        return DB::table($table)
                    ->where($column, $value)
                    ->first(); //it must be first(); do not change!
    }

    public static function updateRecord($table, $column, $value, $updatesArray)
    {
        //this function updates a single row of a table

        return DB::table($table)
            ->where($column, $value)
            ->update($updatesArray);
    }

    public static function insertRecord($table, $insertArray)
    {
        //insert a single row and return the ID.(if the table has auto-increment)
        return DB::table($table)
                ->insertGetId($insertArray);
    }

    public static function dateFormatter($date)
    {
        //this function returns Carbon formatted date. E.g: Sat, Nov 28, 2017 10:19 AM
        $date = (string) $date;
        $carbon = new \Carbon\Carbon($date);
        return $carbon->toDayDateTimeString();        
    }

    public static function RRRFormatter($RRR, $length = 4)
    {
        $RRR = (string) $RRR;
        $array = str_split($RRR, $length);
        
        return implode("-", $array);

    }

    public static function phoneNoFormatter($phoneNo)
    {
        //this function formats a phone number to +234
        $actualNo = (string) substr($phoneNo, 1);
        $formatted = "+234".$actualNo;
        return $formatted;

    }

    public static function emailExists($email)
    {
        //this function checks if the email exists
        $count = DB::table('users')
            ->where('email', $email)
            ->count();

       return $count > 0 ? true : false;

    } 

    public static function generateEmail($surname, $firstName)
    {
        /*
        this function generate and returns a unique email address
        NB: this does not create the email in users table or Microsoft Graph
        */

        //cleanse $firstname and $surname with RegEx
        //most of the surnames and firstnames are joined with othernames, so split it eg: firstname = john doe; split as firstname = john

        $regEx = '/\W/';
        $surname = explode(' ', $surname);
        $surname = $surname[0];
        $surname = strtolower(preg_replace($regEx, '', $surname));

        $firstName = explode(' ', $firstName);
        $firstName = $firstName[0];
        $firstName = strtolower(preg_replace($regEx, '', $firstName));
        
        $concatName = $surname.".".$firstName;
        $emailExtention = "@".static::basicInfo()->domainExtention;

        //create email address
        $email = $concatName.$emailExtention;

        $count = 0;
        while(static::emailExists($email))
        {
            $count++;
            $email = $concatName.$count.$emailExtention;
        }

        return $email;
    }

    public static function generatePassword()
    {
        //this function generates a random password everytime
        $password = "NTI@".rand(1000, 999999);
        return $password;
    }


    /*
    |--------------------------------------------------------------------------
    | ZONES, STATES AND LGAs RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getZones()
    {
        //returns the six geo-political zones in Nigeria
        return DB::table('zones')->get();
    }

    public static function getStates($zoneId = NULL)
    {
        //if zone is NULL, the function returns the 36 states in Nigeria, else it returns the states in that zone
        $states = NULL;

        if(isset($zoneId))
        {
            $states = DB::table('states')->where('zone_id', $zoneId)->get();
        }
        else
        {
            $states = DB::table('states')->get();
        }

        return $states;
    }

    public static function getLGA($stateId)
    {
        //returns the LGAs for a particular state
        return DB::table('lga')
            ->where('state_id', $stateId)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC SESSIONS AND SEMESTERS RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getAcademicSessionsInfo()
    {
        //this function returns information about all the academic sessions and semesters
        return DB::select("SELECT academic_semesters.id as semesterId,
        academic_semesters.status as semesterStatus,
        academic_sessions.id as sessionId,
        academic_sessions.status as sessionStatus,
        academic_sessions.year_name as year,
        academic_sessions.name as academicSession,
        academic_semesters.name as semester,
        academic_semesters.start_date as semesterStart,
        academic_semesters.end_date as semesterEnd
        FROM academic_semesters, academic_sessions
        WHERE academic_session_id = academic_sessions.id");

    }

    public static function getCurrentAcademicSessionInfo()
    {
        //returns information about the current academic session and semester
        $data = DB::select("SELECT academic_semesters.id as semesterId,
                           academic_sessions.id as sessionId,
                           academic_sessions.year_name as year,
                           academic_sessions.name as academicSession,
                           academic_semesters.name as semester,
                           academic_semesters.start_date as semesterStart,
                           academic_semesters.end_date as semesterEnd
                           FROM academic_semesters, academic_sessions
                           WHERE academic_semesters.status = :status
                           AND academic_session_id = academic_sessions.id
                           ORDER BY academic_semesters.id DESC
                           LIMIT 1",
                           ["status" => "green"]);

        return (! empty($data)) ? $data[0] : null;   
                           
    }

    public static function getAcademicInfo($semesterId)
    {
        //this function returns info of a particular semesterId
        $data = DB::select("SELECT academic_semesters.id as semesterId,
        academic_sessions.id as sessionId,
        academic_sessions.year_name as year,
        academic_sessions.name as academicSession,
        academic_semesters.name as semester,
        academic_semesters.start_date as semesterStart,
        academic_semesters.end_date as semesterEnd
        FROM academic_semesters, academic_sessions
        WHERE academic_semesters.id = :semesterId
        AND academic_session_id = academic_sessions.id",
        ["semesterId" => $semesterId]);

        return (! empty($data)) ? $data[0] : null;   

    }

    /*
    |--------------------------------------------------------------------------
    | PROGRAMMES, SPECIALIZATIONS AND LEVEL RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getProgrammes()
    {
        //this function returns all the programmes NTI offer
        return DB::table('programmes')->get();
    }

    public static function getSpecializations($programmeId, $status = 'active')
    {
        //this function returns all the active or inactive specializations for a particular programme
    
        return DB::table('specializations')
                ->where([
                    ['programme_id', $programmeId],
                    ['status', $status]
                ])
                ->get();
        
    }

    public static function isPractical($specializationId)
    {
        //this function checks if a specialization is a practical specialization or not
        $count = DB::table('specializations')
            ->where([
                ['id', $specializationId],
                ['practical', 1]
            ])
            ->count();

        return ($count == 1) ? true : false;     
    }

    public static function getLevels($programmeId)
    {
        //this function returns the levels for a particular programme
        return DB::table('levels')
            ->where('programme_id', $programmeId)
            ->get();
    }

    public static function getLevelName($programmeId, $level)
    {
        //this function returns a more human friendly Level name. E.g: Year 1 or 100 Level or N/A etc
        $data = DB::table('levels')
                ->where([
                    ['programme_id', $programmeId],
                    ['level', $level]
                ])
                ->first();

        return @$data->level_name;                    

    }


    /*
    |--------------------------------------------------------------------------
    | STUDY CENTERS RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getStudyCenters($stateId, $programmeId, $status = 'active')
    {
     
        //this function returns all the active or inactive study centers
            return DB::table('study_centers')
                ->where([
                    ['state_id', $stateId],
                    ['programme_id', $programmeId],
                    ['status', $status]
                  ])
                ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | COURSES RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getCourses($programmeId, $specializationId, $level, $semester, $status = "active")
    {
        return DB::table('courses')
            ->where([
                ['programme_id', $programmeId],
                ['specialization_id', $specializationId],
                ['level', $level],
                ['semester', $semester],
                ['status', $status]
            ])
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | FEES RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getFeeId()
    {
        //returns the ID of a fee name for easy referencing. DO NOT DELETE (used in so many places)
        $fees = [
            "applicationFee" => 2,
            "tuitionFee" => 19,
            "carryoverFee" => 3            
        ];

        return static::arrayToJson($fees);
    }

    public static function getFeeDefinition($feeId, $programmeId, $specializationId, $level, $semester, $category = 'default')
    {

        //returns a single fee definition
        
        $data = DB::select("SELECT * 
                             FROM fee_definitions
                             WHERE fee_id = :feeId
                             AND programme_id = :programmeId
                             AND specialization_id = :specializationId
                             AND level = :level
                             AND semester = :semester
                             AND category = :category",
                             [
                                 "feeId" => $feeId,
                                 "programmeId" => $programmeId,
                                 "specializationId" => $specializationId,
                                 "level" => $level,
                                 "semester" => $semester,
                                 "category" => $category
                             ]);

        return (! empty($data)) ? $data[0] : null;      

    }

    public static function paymentType($number)
    {
        //this function returns the title of a payment e.g: Full, First Installment
        $type = "Full Payment";

        if($number > 0)
            $type = static::numberToPosition($number) . " Installment";

        return $type;
    }

    public static function getFeeAssignments($key, $value)
    {
        //returns all the fee_assignments for the $key and $value
        return DB::table('fee_assignments')
            ->where([
                ['param', $key],
                ['val', $value]
            ])
            ->get();
        
    }

    public static function getRRRInfo($RRR)
    {
        //this function returns information about an RRR

        $RRR = (string) $RRR;
        $data = DB::table('transactions')
            ->where('remitaBefore->RRR', $RRR)
            ->first();

        return $data;    
    }

    /*
    |--------------------------------------------------------------------------
    | APPLICANTS RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getApplicantsWithAppNo($semesterId, $programmeId)
    {
        //returns the list of applicants in the semesterID and programmeID that has been given ApplicationNo
        return DB::table('applicants')
        ->whereNotNull('app_no')
        ->where([
                ['semester_id', $semesterId],
                ['programme_id', $programmeId]
            ])
            ->get();
    }

    public static function generateApplicantNo($semesterId, $programmeId, $academicYear)
    {
        //this function generates Application number in this format: APP/PROGRAMME_ABBR/YEAR/UNIQUE_NO
        //APP/NCE/2017/009

        $programmeAbbr = static::getInfo('programmes', 'id', $programmeId)->abbr;
        $uniqueNo = count(static::getApplicantsWithAppNo($semesterId, $programmeId)) + 1;
        $uniqueNoZF = static::zeroFill($uniqueNo, 3);

        $applicantNo = "APP/{$programmeAbbr}/{$academicYear}/{$uniqueNoZF}";

        return $applicantNo;
    
    }
    
    /*
    |--------------------------------------------------------------------------
    | STUDENTS RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getStudents($entryYear, $programmeId)
    {
        //returns the list of students in the entryYear and programmeID
        return DB::table('students')
            ->where([
                ['entry_year', $entryYear],
                ['programme_id', $programmeId]
            ])
            ->get();
    }

    public static function generateStudentNo($entryYear, $programmeId)
    {
        /*this function generates Student Registration and Exam number in this format:
        SCHL_ABBR/PROGRAMME_ABBR/YEAR/UNIQUE_NO*/
        
        $schlAbbr = static::basicInfo()->schoolAbbr;
        $programmeAbbr = static::getInfo('programmes', 'id', $programmeId)->abbr;

        $uniqueNo = count(static::getStudents($entryYear, $programmeId)) + 1;
        $uniqueNoZF = static::zeroFill($uniqueNo, 3);
        $examNo = static::zeroFill($uniqueNo, 6);
        $regNo = "{$schlAbbr}/{$programmeAbbr}/{$entryYear}/{$uniqueNoZF}";

        $data = [
            "examNo" => $examNo,            
            "regNo" => $regNo
        ];

        return static::arrayToJson($data);

    }
    
    /*
    |--------------------------------------------------------------------------
    | STAFF RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

}