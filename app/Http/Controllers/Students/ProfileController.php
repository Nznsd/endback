<?php

namespace NTI\Http\Controllers\Students;


use \NTI\Repository\Services\NTI as NTIServices;
use \NTI\Repository\Modules\StudentsModule;

use Illuminate\Http\Request;
use NTI\Http\Controllers\Controller;

class ProfileController extends Controller
{

    public $profileClass = "active";
    public $page = "View Profile";

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
        //this function displays the profile page

        $currentSession = NTIServices::getCurrentAcademicSessionInfo();        
        $level = NTIServices::getLevelName($this->student->getMyProfile()->programmeId, $this->student->getMyLevelIn($currentSession->semesterId));
        

        $data = [
            "page" => $this->page,
            "profileClass" => $this->profileClass,
            "tab1" => "active",
            "avatar" => $this->student->getDefaultAvatar(),
            "fullname" => $this->student->getFullname(),
            "myProfile" => $this->student->getMyProfile(),
            "NTIServices" => "\NTI\Repository\Services\NTI",
            "level" => $level,
            "genderArray" => ["Male", "Female"],
            "maritalStatusArray" => ["Single", "Married", "Widowed", "Divorced"]
        ];

        return view('students.profile.index', $data);
    }

    public function update(Request $request)
    {
        //this function updates student's profile

        $this->validate($request, [
            'recoveryEmail' => 'required|email',
            'phoneNo' => 'required',
            'dob' => 'required|date',
            'address' => 'required'
        ]);

        $student = \NTI\Models\Student::find($this->student->getMyProfile()->studentId);
        $student->recovery_email = $request->recoveryEmail;
        $student->phone = $request->phoneNo;
        $student->dob = $request->dob;
        $student->gender = $request->gender;
        $student->marital_status = $request->maritalStatus;
        $student->address = $request->address;
        $student->save();

        return back()->with("status", "Profile Updated Successfully");

    }

}
