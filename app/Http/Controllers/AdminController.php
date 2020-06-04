<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Department;
use App\Course;
use App\Instructor;
class AdminController extends Controller
{

    //STUDENT
    public function getAddStudent(Request $request)
    {
        return view('admin.student.create');
    }

    public function postAddStudent(Request $request)
    {
        $this->validate($request,[
            // 'email' => 'email|max:255|required',
            'firstName' => 'max:255|alpha|required',
            'lastName'=> 'max:255|alpha|required',
            // 'level' =>' integer|lt:4|required',
            // 'gpa' => 'numeric|lt:4| gt:0',
            // 'userName' => 'max:255|alpha|required',
            // 'password' => 'required',
            'gender' => 'required',
            'department_id' => 'required',
        ]);
        $user = User::create([
            'email' => $request->firstName."_".$request->lastName."@cms.com",
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'user_name' => $request->userName,
            'password' => \Hash::make("P@ssw0rd"),
            'gender' => $request->gender,
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'level' => "1",
            'gpa' => "0",
            'department_id' => '1',
            'unique_id' => '0',
        ]);
        // $uniqueId = $student->created_at->format('Y');
        // $username = strtolower($request->firstName).strtolower($request->lastName).$uniqueId;
        return redirect()->back();
    }

    public function getUpdateStudent($name){
        
        $student = Student::whereHas('user', function($query) use($name){
            $query->where('user_name',$name);
        })->first();
        if($student)
            return view('admin.student.update',compact('student'));
        else
            abort('404');
    }

    public function postUpdateStudent(Request $request, $name)
    {
        // return $request;
        $student = Student::whereHas('user', function($query) use($name){
            $query->where('user_name',$name);
        })->first();
        $this->validate($request, [
            'email' => 'email|max:255',
            'firstName' => 'max:255|alpha',
            'lastName'=> 'max:255|alpha',
            'level' =>' integer|lt:5',
            'gpa' => 'numeric|lt:4| gt:0',
            'gender' => 'numeric',
            'department_id' => 'numeric',
        ]);

        $student->user->update([
            'email' => $request->email,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'gender' => $request->gender,
        ]);
        $student->update([
            'level' => $request->level,
            'gpa' => $request->gpa,
            'department_id' => $request->department_id,

        ]);
        
        return redirect(route('edit.student', $student->user->user_name));
    }

    public function getListStudents(Request $request)
    {
        $level = $request->level;
        $students = Student::where('level',$level)->get();
        return view('admin.student.list',compact('students','level'));
    }

    public function getViewStudent($name)
    {
        $student = Student::whereHas('user', function($query) use($name){
            $query->where('user_name',$name);
        })->first();
        return view('admin.student.view', compact('student'));
    }
    ////////////////
    //COURSE

    public function getAddCourse(Request $request)
    {
        return view('admin.course.create');
    }
    public function postAddCourse(Request $request)
    {
        $this->validate($request, [
           'courseName' => 'max:255|required',
           'courseCode' => 'required|max:255',
           'minStudentsNumber' => 'required|numeric',
           'department_id' => 'required',
           'semester' => 'required',
           'creditHours' => 'numeric|required',
        ]);
        if($course)
        {
            $course = Course::create([
                'name' => $request->courseName,
                'code' => $request->courseCode,
                'min_students_number' => $request->minStudentsNumber,
                'department_id' => $request->department_id,
                'semester' => $request->semester,
                'credit_hours' => $request->creditHours,
            ]);
            return redirect()->back();
        }
        else
        {
            abort('404');
        }
        
    }
    public function getListCourses(Request $request)
    {
        $courses = (new Course)->newQuery();
        if($request->has('department'))
        {
            $courses->where('department_id', $request->department);
        }
        if($request->has('semester'))
        {
            $courses->where('semester', $request->semester);
        }
        $courses = $courses->get();
        return view('admin.course.list', compact('courses'));
    }

    public function getUpdateCourse($code) 
    {
        $course = Course::where('code', $code)->first();
        if($course)
            return view('admin.course.update', compact('course'));
        else
            abort('404');
        // return $course->name;
    }

    public function postUpdateCourse(Request $request, $code)   
    {
        $course = Course::where('code', $code)->first();
        if($course->code)
        {
            $course->update([
                'name' => $request->courseName,
                'code' => $request->courseCode,
                'min_students_number' => $request->minStudentsNumber,
                'department_id' => $request->department_id,
                'semester' => $request->semester,
                'credit_hours' => $request->creditHours,
                
            ]);
            return redirect(route('edit.course',$course->code));
        }
        else
            abort('404');
    }
    ///////////////////
    //Instructors

    public function createInstructor(Request $request)
    {
        return view('admin.instructor.create');
    }

    public function storeInstructor(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|max:255|required',
            'firstName' => 'max:255|alpha|required',
            'lastName'=> 'max:255|alpha|required',
            'userName' => 'max:255|alpha|required',
            'password' => 'required',
            'gender' => 'required',
            'department_id' => 'required',
        ]);
        $user = User::create([
            'email' => $request->email,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'user_name' => $request->userName,
            'password' => \Hash::make($request->password),
            'gender' => $request->gender,
            'role' => '2',

        ]);
        $instructor = Instructor::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
        ]);
        return redirect()->back();
    }

    public function editInstructor($name){
        
        $instructor = Instructor::whereHas('user', function($query) use($name){
            $query->where('user_name',$name);
        })->first();
        if($instructor)
            return view('admin.instructor.update',compact('instructor'));
        else
            abort('404');
    }

    public function updateInstructor(Request $request, $name)
    {
        // return $request;
        $instructor = Instructor::whereHas('user', function($query) use($name){
            $query->where('user_name',$name);
        })->first();
        $this->validate($request, [
            'email' => 'email|max:255',
            'firstName' => 'max:255|alpha',
            'lastName'=> 'max:255|alpha',
            'gender' => 'numeric',
            'department_id' => 'numeric',
        ]);

        $instructor->user->update([
            'email' => $request->email,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'gender' => $request->gender,
        ]);
        $instructor->update([
            'level' => $request->level,
            'department_id' => $request->department_id,
        ]);
        
        return redirect(route('edit.instructor', $instructor->user->user_name));
    }

    public function listInstructors(Request $request)
    {
        $department_id = $request->department_id;
        $instructors = new Instructor;
        if($request->has('department_id'))
        {
            $instructors = $instructors->where('department_id',$department_id);
        }
        $instructors = $instructors->get();
        return view('admin.instructor.list',compact('instructors','department_id'));
    }

    public function showInstructor($name)
    {
        $instructor = Instructor::whereHas('user', function($query) use($name){
            $query->where('user_name',$name);
        })->first();
        return view('admin.instructor.view', compact('instructor'));
    }

}
