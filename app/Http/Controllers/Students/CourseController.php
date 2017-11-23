<?php

namespace NTI\Http\Controllers\Students;

use \NTI\Repository\Services\NTI as NTIServices;
use \NTI\Repository\Modules\StudentsModule;

use NTI\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public $courseClass = "active";
    public $page = "Course Registration";

    protected $student;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:student');

        $this->middleware(function($request, $next){
            $this->student = new StudentsModule(auth()->id());
            return $next($request);
        });

    }

    public function index()
    {

        //this function displays courses for course registration

        $semesterId = NTIServices::getCurrentAcademicSessionInfo()->semesterId;

        $courses = $this->student->getMyCourses($semesterId);
        $paid = true;

        //check if the students is owing tution fee or not
        if($this->student->isOwingTuitionFee($semesterId) && ! $this->student->isAScholarshipStudent($semesterId, NTIServices::getFeeId()->tuitionFee))
            $paid = false;
        
        //get the student's registered courses for that semesterId    
        $registeredCourses = @json_decode($this->student->getMyRegisteredCourses('normal', $semesterId));
            
        $data = [
            "page" => $this->page,
            "courseClass" => $this->courseClass,
            "tab1" => "active",
            "semesterId" => $semesterId,
            "courses" => $courses,
            "paid" => $paid,
            "registeredCourses" => (array) $registeredCourses,
            "totalUnits" => 0,
            "NTIServices" => "\NTI\Repository\Services\NTI",
            "studentInstance" => $this->student            
            
        ];

        return view('students.courses.index', $data);

    }

    public function carryover()
    {
        //this function displays courses for carryover registration

        $semesterId = NTIServices::getCurrentAcademicSessionInfo()->semesterId;
        $feeId = NTIServices::getFeeId()->carryoverFee;        
        $paid = true;

        //check if the students is owing tution fee or not
        if($this->student->isOwingFee($feeId, $semesterId))
            $paid = false;

        //get the student's registered c/o courses for that semesterId    
        $registeredCourses = $this->student->getMyRegisteredCourses('carryover', $semesterId);
            
        $data = [
            "page" => $this->page,
            "courseClass" => $this->courseClass,
            "tab2" => "active",
            "semesterId" => $semesterId,
            "paid" => $paid,
            "studentInstance" => $this->student,
            "registeredCourses" => $registeredCourses,
            "allCourses" => $this->student->getAllCoursesForMySpecializationId(),
            "NTIServices" => "\NTI\Repository\Services\NTI"
        ];

        return view('students.courses.carryover', $data);
        
    }

    public function history()
    {
        //this function displays course registration history

        $courseHistory = $this->student->getMyCourseHistory();

        $data = [
            "page" => $this->page,
            "courseClass" => $this->courseClass,
            "tab3" => "active",
            "courseHistory" => $courseHistory,
            "sn" => 1,
            "NTIServices" => "\NTI\Repository\Services\NTI",
            "studentInstance" => $this->student
        ];

        return view('students.courses.history', $data);
    }

    public function register(Request $request)
    {

        //this function registers courses. Either Normal or C/O

        //recieve POST courses array
        $semesterId = $request->semesterId;        
        $regType = $request->regType;
        $courses = json_encode($request->data);

        $courseRegistrationId = 0;

        //check if course reg already exists for the regType for the current semseter
        $courseRegistrationId = $this->student->courseRegExists($regType, $semesterId);

        if($courseRegistrationId !== false)
        {
            //update course registration
            $courseReg = \NTI\Models\CourseRegistration::find($courseRegistrationId);
            $courseReg->courses = $courses;
            $courseReg->save();

        }
        else
        {
            $studentId = $this->student->getMyProfile()->studentId;

            //insert course registration
            $courseReg = new \NTI\Models\CourseRegistration;
            $courseReg->student_id = $studentId;
            $courseReg->semester_id = $semesterId;
            $courseReg->registration_type = $regType;
            $courseReg->courses = $courses;
            $courseReg->save();

            $courseRegistrationId = $courseReg->id;
        }

        //redirect to course reg. form    
        $url = "/students/course/form/". encrypt($courseRegistrationId);
        return redirect($url);

    }

    public function form($courseRegistrationId)
    {
        //this function displays course registration form
        $courseRegistrationId = decrypt($courseRegistrationId);
        $courses = NTIServices::getInfo('course_registrations', 'id', $courseRegistrationId);

        $currentSession = NTIServices::getCurrentAcademicSessionInfo();
        $level = NTIServices::getLevelName($this->student->getMyProfile()->programmeId, $this->student->getMyLevelIn($currentSession->semesterId));

        $feeTypeId = ($courses->registration_type == 'normal') ? NTIServices::getFeeId()->tuitionFee : NTIServices::getFeeId()->carryoverFee;

        $transactionDetails = $this->student->getCourseTransactionDetails($feeTypeId, $courses->semester_id);
        $RRR = @json_decode($transactionDetails->remitaAfter)->RRR;

        $data = [
            "myProfile" => $this->student->getMyProfile(),
            "fullname" => $this->student->getFullname(),
            "courses" =>  $courses,
            "sn" => 1,
            "NTIServices" => "\NTI\Repository\Services\NTI",
            "avatar" => "",
            "level" => $level,
            "semester" => NTIServices::numberToPosition($currentSession->semester),
            "session" => $currentSession->academicSession,
            "type" => ($courses->registration_type == 'normal') ? "Course" : "Carryover",
            "totalUnits" => 0,
            "RRR" => $RRR
        ];

        return view('students.courses.form', $data);
    }

    
}
