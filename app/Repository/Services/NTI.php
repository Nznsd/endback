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

    public static function basicInfo()
    {
        //this function contains basic info about the school
        $info = [
            "schoolName" => "National Teachers' Institute",
            "schoolAbbr" => "NTI", //useful for Registartion No. e.g: NTI/NCE/2017/001
            "schoolMoto" => "",
            "schoolLogo" => "",
            "schoolAddress" => "",
            "schoolDescription" => "",
            "domainExtention" => "nti.edu.ng"
        ];

        return static::arrayToJson($info);
    }

    public static function zeroFill($uniqueNo, $maximumDigits)
    {
        //this function appends zeros to a number
        $uniqueNo = (string) $uniqueNo;
        $strLength = strlen($uniqueNo);

        $peak = $maximumDigits - $strLength;
        
        if($peak <= 2)
            $peak = $maximumDigits; 

        $array = [];

        for($i = 0; $i < $peak; $i++)
            $array[] = "0";            
            
        $zeroFill = implode('',$array);
        $zeroFilled = "{$zeroFill}{$uniqueNo}";

        return (string)$zeroFilled;

    }

    public static function getInfo($table, $primaryKey = null, $value = null)
    {
        //returns information about a record. Works like eloquent find() if primary key is persent
        // and all() if no promary key
        // and 
        if($value == null && $primaryKey == null)
        {
            return DB::table($table)
                ->get();
        }else if($value == null && isset($primaryKey))
        {
            return DB::table($table)
                ->where('id', $primaryKey)
                ->first();
        }
        return DB::table($table)
                    ->where($primaryKey, $value)
                    ->get();
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
        $regEx = '/\W/';
        $surname = strtolower(preg_replace($regEx, '', $surname));
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
         $query = DB::select("SELECT academic_semesters.id as semesterId,
                           academic_sessions.id as sessionId,
                           academic_sessions.year_name as year,
                           academic_sessions.name as academicSession,
                           academic_semesters.name as semester,
                           academic_semesters.start_date as semesterStart,
                           academic_semesters.end_date as semesterEnd
                           FROM academic_semesters, academic_sessions
                           WHERE academic_semesters.status = :status
                           AND academic_session_id = academic_sessions.id",
                           ["status" => 'green']);
        return $query[0];
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
            ->where('proramme_id', $programmeId)
            ->get();
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

    public static function getCourses($programmeId, $specializationId, $level, $semester)
    {
        return DB::table('courses')
            ->where([
                ['programme_id', $programmeId],
                ['specialization_id', $specializationId],
                ['level', $level],
                ['semester', $semester]
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
        //returns the ID of a fee name
        $fees = [
            "application_fee" => 2,
            "tuition_fee" => 19            
        ];

        return static::arrayToJson($fees);
    }

    public static function getFeeDefinition($feeId, $programmeId, $specializationId, $level, $semester, $category)
    {

        //returns a single fee definition
        $specializationId = (static::isPractical($specializationId)) ? $specializationId : 0;

        $query = DB::select("SELECT * 
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

          return $query[0];                   
    }

    public static function getFeeAssignments($semesterId, $key, $value)
    {
        
    }

    public static function getTransactions($semesterId, $key, $value)
    {
        
    }

    /*
    |--------------------------------------------------------------------------
    | APPLICANTS RELATED SERVICES
    |--------------------------------------------------------------------------
    |
    */

    public static function getApplicants($semesterId, $programmeId)
    {
        //returns the list of applicants in the semesterID and programmeID
        return DB::table('applicants')
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

        $programmeAbbr = static::getInfo('programmes', $programmeId)->abbr;
        $uniqueNo = count(static::getApplicants($semesterId, $programmeId)) + 1;
        $uniqueNoZF = static::zeroFill($uniqueNo, 2);

        $applicantNo = "APP/{$programmeAbbr}/{$academicYear}/{$uniqueNoZF}";

        return $applicantNo;
    
    }

    public static function getSubjects()
    {
        return DB::table('subjects')->get();
    }
    
    public static function getGrades($id = null)
    {
        if(!isset($id))
            return DB::table('grades')->get();
        else 
            return DB::table('grades')
                ->where('certificate_id', $id)
                ->get();
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
        $programmeAbbr = static::getInfo('programmes', $programmeId)->abbr;

        $uniqueNo = count(static::getStudents($entryYear, $programmeId)) + 1;
        $uniqueNoZF = static::zeroFill($uniqueNo, 2);
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