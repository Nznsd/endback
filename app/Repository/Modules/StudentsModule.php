<?php

/*
*   NAME: STUDENTS MODULE
*   DESCRIPTION: This script handles everything concerning a particular student.
*   AUTHOR: SADIQ LUKMAN
*   DATE: 
*/

namespace NTI\Repository\Modules;

use NTI\Repository\Services\NTI as NTIServices;
use Illuminate\Support\Facades\DB;

class StudentsModule{

    protected $userId;
  
	public function __construct($userId)
	{
	    $this->userId = $userId;
	}

    /*
    |--------------------------------------------------------------------------
    | Dashboard Related Codes
    |--------------------------------------------------------------------------
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Profile Related Codes
    |--------------------------------------------------------------------------
    |
    */

    public function getMyProfile()
    {
        //this function returns the student's data
        $userId = $this->userId;

        $data = DB::select("SELECT users.id as userId,
                                   users.created_at as userCreatedAt, 
                                   users.email as email,
                                   users.role as role,
                                   users.status as status,
                                   students.id as studentId,
                                   students.reg_no as regNo,
                                   students.exam_no as examNo,
                                   students.surname as surname,
                                   students.firstname as firstName,
                                   students.othername as otherName,
                                   students.recovery_email as recoveryEmail,
                                   students.phone as phoneNo,
                                   students.dob as dob,
                                   students.gender as gender, 
                                   students.marital_status as maritalStatus,
                                   students.address as address, 
                                   sor, sor_lga, soo, soo_lga,
                                   students.programme_id as programmeId,
                                   students.specialization_id as specializationId,
                                   students.study_center_id as studyCenter,
                                   students.entry_level as entryLevel,
                                   students.entry_year as entryYear,
                                   students.exit_year as exitYear,
                                   students.entry_semester as entrySemester,
                                   students.entry_type as entryType,
                                   students.applicant_id as applicantId
                                   FROM users, students
                                   WHERE users.id = :userId
                                   AND students.user_id = users.id",
                                   [
                                       "userId" => $userId
                                   ]);

        return (! empty($data)) ? $data[0] : NULL;                           

    }

    public function getFullname()
    {
        //returns fullname formatted
        $data = $this->getMyProfile();
        $fullname = strtoupper($data->surname)." ".ucwords($data->firstName)." ".ucwords($data->otherName);

        return $fullname;
    
    }

    public function getDefaultAvatar()
    {
        $surname = strtoupper(substr($this->getMyProfile()->surname, 0, 1));
        $firstname = strtoupper(substr($this->getMyProfile()->firstName, 0, 1));

        return $surname.$firstname;
    }

    public function countMyDeferment()
    {
        //returns the number of acadmic sessions the student has deferred
        $studentId = $this->getMyProfile()->studentId;

        return DB::table('deferments')
            ->where('student_id', $studentId)
            ->count();
    }

    public function iDeferred($semesterId)
    {
        //checks if the student deferred the semesterId or not
        $studentId = $this->getMyProfile()->studentId;
        
        $count = DB::table('deferments')
                    ->where([
                        ['student_id', $studentId],
                        ['semester_id', $semesterId]
                        ])
                    ->count();

        return ($count > 0) ? true : false;            
    }

    public function getMyLevelIn($semesterId)
    {

        /*This function returns the student's level in that semesterId.
         It returns INT (1, 2, 3, 4)*/

        //get the year of the semesterId
        $yearOfSemesterId = NTIServices::getAcademicInfo($semesterId)->year;

        $myProfile = $this->getMyProfile();
        $programmeId = @$myProfile->programmeId;
        $entryLevel = @$myProfile->entryLevel;
        $entryYear = @$myProfile->entryYear;
        $level = 1; //more like default level

        $minDuration = NTIServices::getInfo('programmes', 'id', $programmeId)->min_duration;
        
        //if programme is NCE or B.ED
        $nceId = NTIServices::basicInfo()->nceId;
        $bdpId = NTIServices::basicInfo()->bdpId;

        if($programmeId == $nceId || $programmeId == $bdpId) 
        {
            $countDeferment = $this->countMyDeferment(); //counts the number of academic sessions the students deferred
          
            $supposedlyNoOfYears = $yearOfSemesterId - $entryYear; //the number of years the student is supposed to have spent in the school. E.g: 2017 - 2014 = 3
            $supposedlyLevel = $supposedlyNoOfYears + $entryLevel; //E.g: 3 + 1 = 4 (Level 4)
            $actualLevel = $supposedlyLevel - $countDeferment; 

            //for spill over students
            $level = ($actualLevel > $minDuration) ? $minDuration : $actualLevel;
        }
       
        return $level;

    }

    public function getMyFirstEverSemesterId()
    {                                                                                                                                                                                       
        //this function returns the first semesterId the student met when he/she joined NTI

        //Get the entry year and semester of the student 
        $entryYear = $this->getMyProfile()->entryYear;
        $entrySemester = $this->getMyProfile()->entrySemester;

        //Get the semesterId of the entry year and entry semester
        $data = DB::select("SELECT academic_semesters.id as semesterId
                            FROM academic_semesters, academic_sessions
                            WHERE academic_semesters.name = :semester
                            AND academic_sessions.year_name = :year
                            AND academic_sessions.id = academic_session_id", 
                            [
                                "semester" => $entrySemester,
                                "year" => $entryYear
                            ]);

        return (! empty($data)) ? $data[0] : null;   

    }

    public function getMySemesterIds($commissioned = true)
    {
        /*this function returns all the semesterIds of the student. 
        If $commissioned = true, return all the semesterIds from when this portal was commissioned to the current semester;
        If $commissioned = false, return all the semesterIds the student have met in the school to the current semester*/

        $myFirstEverSemester = @($commissioned) ? NTIServices::basicInfo()->commissionedSemesterId : $this->getMyFirstEverSemesterId()->semesterId;
        $currentSemesterId = @NTIServices::getCurrentAcademicSessionInfo()->semesterId;

        $data = DB::table('academic_semesters')
                    ->whereBetween('id', [$myFirstEverSemester, $currentSemesterId])
                    ->select('id')
                    ->get();

        return $data;    
    }

    /*
    |--------------------------------------------------------------------------
    | Fee Related Codes
    |--------------------------------------------------------------------------
    |
    */

    /* OUTSTANDING FEES FUNCTION */

    public function getMyPaidTransactions($feeId, $semesterId)
    {

        /*this function returns transaction details about a feeId for a particular semesterId.
         NB: It can return more than one record because of installment payments */
        $studentId = $this->getMyProfile()->studentId;
        
        return DB::table('transactions')
            ->where([
                ['fee_id', $feeId],
                ['semester_id', $semesterId],
                ['param', 'student'],
                ['val', $studentId],
                ['status', '!=' ,'unpaid'] // a payment is paid as long as the status is not UNPAID ( I.E status = paid or waived)
            ])
            ->get();

    }

    public function getTuitionFeeDefinitionToBePaid($semesterId)
    {
        //this function returns the tuition fee definition to be paid for a particular semesterId
        $feeId = @NTIServices::getFeeId()->tuitionFee;
        $programmeId = @$this->getMyProfile()->programmeId;
        $specializationId = @$this->getMyProfile()->specializationId;
        $level = @$this->getMyLevelIn($semesterId);
        $semester = @NTIServices::getAcademicInfo($semesterId)->semester;
        
        $definition = NTIServices::getFeeDefinition($feeId, $programmeId, $specializationId, $level, $semester);
        return $definition;
    }

    public function isOwingTuitionFee($semesterId)
    {
        //this function returns true if the student is owing tuition fee for a particular semesterId
        
        $tuitionFeeAmount = @$this->getTuitionFeeDefinitionToBePaid($semesterId)->amount;
        $feeId = @NTIServices::getFeeId()->tuitionFee;

        $transactions = $this->getMyPaidTransactions($feeId, $semesterId);
        $sum = 0;

        foreach($transactions as $value)
        {
            $sum += $value->amount;
        }

        return ($tuitionFeeAmount <= $sum) ? false : true; //student is owing if $totalAmountPaid > $tuitionFeeAmount  

    }

    public function isAScholarshipStudent($semesterId, $feeId)
    {
        /*this function returns true if the student was a scholarship student for that semesterId for a particular fee payment.
        NB: Not just being a scholarship student but the scholarship must be paid*/

        $regNo = @$this->getMyProfile()->regNo;

        $data = @DB::table('beneficiaries')
            ->where([
                ['fee_id', $feeId],
                ['semester_id', $semesterId],
                ['param', 'student'],
                ['val', $regNo]
            ])
            ->first();

        $scholarshipId = @$data->scholarship_id;     
        
        //check that the scholarshipId status is paid or waived for some reason on transaction table    
        $status = DB::table('transactions')
                ->where([
                    ['fee_table', 'scholarships'],
                    ['fee_table_id', $scholarshipId],
                    ['status', '!=', 'unpaid'] 
                ])
                ->count();

            return ($status > 0) ? true : false;

    }

    public function getOutstandingTuitionFees()
    {
        //this function returns all the outstanding tuition fees semester info the student hasn't paid

        //get all my semesterIds from when portal was commissioned
        $mySemesterIds = $this->getMySemesterIds();
        $outstandingTuitionFeeSemesters = [];

        //loop through. If im owing for the semesterId and i'm not a scholarship student for that semesterId, push the semesterId into outstandingTuitionFeeSemester array
        foreach($mySemesterIds as $value)
        {
            $semesterId = $value->id;

            if($this->isOwingTuitionFee($semesterId) && ! $this->isAScholarshipStudent($semesterId, NTIServices::getFeeId()->tuitionFee))
            {
                //get details for the semesterId and push into the array
                $outstandingTuitionFeeSemesters[] = NTIServices::getAcademicInfo($semesterId);
            }

        }

        return $outstandingTuitionFeeSemesters;

    }

    /*FEE ASSIGNMENT FUNCTIONS*/

    public function getMyPaidFeeAssignmentTransactions($feeAssignmentId)
    {

        //this function returns the transaction details of a fee assignment that has been paid or waived
        $studentId = @$this->getMyProfile()->studentId;
        
        return DB::table('transactions')
            ->where([
                ['param', 'student'],
                ['val', $studentId],
                ['status', '!=', 'unpaid'],
                ['fee_table', 'fee_assignments'],
                ['fee_table_id', $feeAssignmentId]
            ])
            ->get();

    }

    public function isOwingFeeAssignment($feeAssignmentObject)
    {
        //this function returns true if the student is owing a particular fee assignment. 
        $amountAssigned = @$feeAssignmentObject->amount; 

        $transactions = $this->getMyPaidFeeAssignmentTransactions($feeAssignmentObject->id);
        $sum = 0;

        foreach ($transactions as $value) {
            $sum += $value->amount; //all the amount paid
        }

        return ($amountAssigned <= $sum) ? false : true;

    }

    public function getMyFeeAssignments($semesterId)
    {
        /*this function returns all the fee assignments that applies to me.
         NB: $semesterId is used to get the level of the student and as WHERE semester_id = $semesterId in the query. Read the code for more info*/

        $regNo = @$this->getMyProfile()->regNo;
        $programmeId = @$this->getMyProfile()->programmeId;
        $specializationId = @$this->getMyProfile()->specializationId;
        $level = @$this->getMyLevelIn($semesterId);

        return DB::select("SELECT *
                            FROM fee_assignments
                            WHERE semester_id = {$semesterId} 
                            AND ((param = 'programme' AND val = '{$programmeId}' AND level = {$level})
                            OR (param = 'specialization' AND val = '{$specializationId}' AND level = {$level})
                            OR (param = 'student' AND val = '{$regNo}' ))");

    }

    public function getOutstandingFeeAssignments()
    {
        //this function returns all your fee assigments that you havn't paid
        $outstandingFeeAssignments = [];

        //get all my semesterIds from when portal was commissioned
        $mySemesterIds = $this->getMySemesterIds();

        foreach ($mySemesterIds as $value) {
            
            //get all my fee assignments for that semesterId
            $feeAssignments = $this->getMyFeeAssignments($value->id);
         
            foreach ($feeAssignments as $feeAssignmentObject) {
                
                if($this->isOwingFeeAssignment($feeAssignmentObject))
                {
                    $outstandingFeeAssignments[] = $feeAssignmentObject;
                }
            }

        }

        return $outstandingFeeAssignments;
    }

    /*PAYMENT HISTORY*/
    public function transactionExists($feeId, $semesterId, $installment, $feeTable, $feeTableId)
    {
        //if the transaction exist: return the transactionId, else return false (used during RRR generation)

        $studentId = $this->getMyProfile()->studentId;
        
        $data = DB::table('transactions')
            ->where([
                ['fee_id', $feeId],
                ['semester_id', $semesterId],
                ['param', 'student'],
                ['val', $studentId],
                ['installment', $installment],
                ['fee_table', $feeTable],
                ['fee_table_id', $feeTableId]
            ])
            ->first();

        return (empty($data)) ? false  : $data->id;
    }

    public function getMyTransactions($semesterId)
    {
        //this function returns all my transactions, even upto when the student was an applicant
        $myProfile = $this->getMyProfile();
        $applicantId = (int) @$myProfile->applicantId;
        $studentId = @$myProfile->studentId;

        $data = DB::select("SELECT * 
                            FROM transactions
                            WHERE semester_id = $semesterId
                            AND ((param = 'applicant' AND val = $applicantId) OR (param = 'student' AND val = $studentId))");

        return $data;                         

    }

    public function printPaymentHistroyTable($semesterId = null)
    {
        //this function print the student's payment history table for a particular semesterId
        $semesterId = (isset($semesterId)) ? $semesterId : NTIServices::getCurrentAcademicSessionInfo()->semesterId;
        $transactions = $this->getMyTransactions($semesterId);

        if(empty($transactions))
        {
            echo"<h4 class='text-info text-center' style='padding: 40px; background-color: #FBFCFD'>You don't have any payment history for this session.
            <br/><a href='".url('students/fees')."'>Make your first payment.</a> </h4>";
        }
        else{

            $sn = 0;

            echo"<table class='table table-hover'>
            
                    <thead class='table-head top-half-pill'>
                
                        <tr>
                            <th>#</th>
                            <th>Fee Description</th>
                            <th>Amount</th>
                            <th>RRR</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                
                    </thead>

                    <tbody  class='table-body bottom-half-pill'>";

                        foreach ($transactions as $transaction) {

                            $sn++;
                            $desc = ucwords(NTIServices::getInfo('fee_types', 'id', $transaction->fee_id)->name); 
                            $remark = $transaction->remark;
                            $amount = number_format($transaction->amount, 2);
                            $RRR = @NTIServices::RRRFormatter(json_decode($transaction->remitaBefore)->RRR);
                            $dateCreated = $transaction->created_at;

                            $transactionId = encrypt($transaction->id);
                            $invoiceHref= url("/students/fees/invoice/{$transactionId}");
                            $receiptHref= url("/students/fees/receipt/{$transactionId}");
                            $action = ($transaction->status != 'unpaid') ? "<a href='{$invoiceHref}' target='_blank'>View Invoice</a> | <a href='{$receiptHref}' target='_blank'>View Receipt</a>" : "<a href='{$invoiceHref}' target='_blank'>View Invoice</a>"; 

                            echo "<tr>
                                <td>{$sn}.</td>
                                <td class='text-primary' title='{$remark}'>{$desc}</td>
                                <td>&#8358;{$amount}</td>
                                <td>{$RRR}</td>
                                <td>{$dateCreated}</td>
                                <td><i class='icon-doc-view'></i>{$action}</td>
                            </tr>";
                        }
            
                    echo"</tbody>
            </table>";

        }

    }

    public function printMyLevelDropdown($commissioned = true)
    {
        //this function prints the student's human readable level in <option>

        $programmeId = $this->getMyProfile()->programmeId;
        $mySemesterIds = $this->getMySemesterIds($commissioned);

        $mySemesterIds = json_decode($mySemesterIds); //convert to proper array
        rsort($mySemesterIds); //sort from latest semesterId to oldest

        foreach ($mySemesterIds as $value) {

            $semesterId = $value->id;
            $semester = NTIServices::getAcademicInfo($semesterId)->semester; //[1 | 2]
            $semesterName = NTIServices::numberToPosition($semester);// [first|second]

            $level = $this->getMyLevelIn($semesterId);
            $levelName = @NTIServices::getLevelName($programmeId, $level);

            echo"<option value='{$semesterId}' data-level='{$level}' data-semester='{$semester}'>{$semesterName} Semester - {$levelName}</option>";
        }

    }

    /*OTHER FEES */
    public function getOtherFees()
    {
        $myProfile = $this->getMyProfile();
        $programmeId = @$myProfile->programmeId;
        $specializationId = @$myProfile->specializationId;

        $currentAcademicSession = NTIServices::getCurrentAcademicSessionInfo();
        $level = $this->getMyLevelIn($currentAcademicSession->semesterId);
        $semester = NTIServices::getAcademicInfo($currentAcademicSession->semesterId)->semester;

        return DB::table('fee_types')
            ->join('fee_definitions', function($join) use ($programmeId, $specializationId, $level, $semester){
                $join->on('fee_types.id', '=', 'fee_definitions.fee_id')
                    ->where([
                        ['programme_id', $programmeId],
                        ['specialization_id', $specializationId],
                        ['level', $level],
                        ['semester', $semester],
                        ['category', 'default'],
                        ['display', 1]
                    ]);     
            })
            ->orderBy('name')
            ->get();
    }

    public function getFeeDefinitionToBePaid($feeId, $semesterId)
    {
        //this function returns the fee definition to be paid for a particular feeId for a particular semesterId

        $programmeId = @$this->getMyProfile()->programmeId;
        $specializationId = @$this->getMyProfile()->specializationId;
        $level = @$this->getMyLevelIn($semesterId);
        $semester = @NTIServices::getAcademicInfo($semesterId)->semester;
        
        $definition = NTIServices::getFeeDefinition($feeId, $programmeId, $specializationId, $level, $semester);
        return $definition;
    }

    public function isOwingFee($feeId, $semesterId)
    {
        //this function returns true if the student is owing a particular fee for a particular semesterId
        
        $feeAmount = @$this->getFeeDefinitionToBePaid($feeId, $semesterId)->amount;

        $transactions = $this->getMyPaidTransactions($feeId, $semesterId);
        $sum = 0;

        foreach($transactions as $value)
        {
            $sum += $value->amount;
        }

        return ($feeAmount <= $sum) ? false : true; //student is owing if $totalAmountPaid > $feeAmount  

    }


    /*
    |--------------------------------------------------------------------------
    | Course Registartion Related Codes
    |--------------------------------------------------------------------------
    |
    */

    /*REGISTER COURSES */
    public function getMyCourses($semesterId)
    {
     
        //this function returns the courses that applies to me for that current academic state (semesterId)

        $myProfile = $this->getMyProfile($semesterId);
        $programmeId = $myProfile->programmeId;
        $specializationId = $myProfile->specializationId;
        $level = $this->getMyLevelIn($semesterId);
        $semester = NTIServices::getAcademicInfo($semesterId)->semester;

        $courses = NTIServices::getCourses($programmeId, $specializationId, $level, $semester);

        return $courses;

    }

    public function getMyRegisteredCourses($regType, $semesterId)
    {
        //this function returns all the courses the student registered for that semesterId
        
        $coursesRegistrationId = $this->courseRegExists($regType, $semesterId);
        $courses = null;

        if($coursesRegistrationId !== false)    
            $courses = NTIServices::getInfo('course_registrations', 'id', $coursesRegistrationId)->courses;
        
        return $courses;    

    }

    public function courseRegExists($regType, $semesterId)
    {

        //if the courseReg exists: return the courseRegId; else return false

        $studentId = $this->getMyProfile()->studentId;
        
        $data = DB::table('course_registrations')
            ->where([
                ['student_id', $studentId],
                ['semester_id', $semesterId],
                ['registration_type', $regType]
            ])
            ->first();

        return (empty($data)) ? false : $data->id;

    }

    /*CARRYOVER */
    public function getAllCoursesForMySpecializationId()
    {
        //this function returns all the courses for my programme and  within my level range (to be used in JS to filter out specific courses)
        
        $myFirstEverSemester = $this->getMyFirstEverSemesterId()->semesterId;
        $currentSemesterId = NTIServices::getCurrentAcademicSessionInfo()->semesterId;

        $startLevel = $this->getMyLevelIn($myFirstEverSemester);
        $endLevel = $this->getMyLevelIn($currentSemesterId);

        $specializationId = $this->getMyProfile()->specializationId;

        $data = DB::table('courses')
            ->where('specialization_id', $specializationId)
            ->whereBetween('level', [$startLevel, $endLevel])
            ->get();

        return $data;    
    }

    /*COURSE HISTORY */
    public function getMyCourseHistory()
    {
        //this function returns all my course registrations history

        $studentId = $this->getMyProfile()->studentId;

        $data = DB::table('course_registrations')
            ->where('student_id', $studentId)
            ->orderBy('semester_id', 'desc')
            ->get();
        
        return $data;

    }

    public function getCourseTransactionDetails($feeId, $semesterId)
    {
        //self explanatory

        $studentId = $this->getMyProfile()->studentId;

        $data = DB::table('transactions')
            ->where([
                ['fee_id', $feeId],
                ['semester_id', $semesterId],
                ['param', 'student'],
                ['val', $studentId],
            ])
            ->first();

        return $data;    
    }

    /*
    |--------------------------------------------------------------------------
    | CA, Results & Exams Related Codes
    |--------------------------------------------------------------------------
    |
    */

    
}