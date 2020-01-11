<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\Subject;
use App\User;
use App\Http\Requests\CourseStore;
use Auth;
use Carbon\Traits\Date;

class CourseController extends Controller
{
    /**
     * List all the courses
     */
    public function index()
    {
        $courses = Course::where('organisationId',Auth::user()->organisationId)->get();

        return view('course.index',compact('courses'));
    }

    /**
     * Load the form for creating new courses/classes along with assigning them the subjects
     */
    public function create()
    {
        $course = new Course();
        $staffs = User::whereHas('roles',function($q){
            $q->where('role_id',3);
        })->get();

        return view('course.form',compact('course','staffs'));

    }

    /**
     * Load the form for edition the course selected
     */
    public function edit($id)
    {
        $course = Course::find($id);
        $subjects = $course->subjects()->get();
        $staffs = User::whereHas('roles',function($q){
            $q->where('role_id',3);
        })->get();

        return view('course.form',compact('course','subjects','staffs'));
    }

    /**
     * Store the course information
     */
    public function store(CourseStore $request)
    {
        $data = $request->validated();
        
        $course = Course::find($data['id']);
        $subjects = [];
        if(is_null($course))
        {
            $course = new Course();
            $course->created_at = Date('Y-m-d H:i:s');
            $course->organisationId = Auth::user()->organisationId;
            $message = "Course ".$data['name']." has been created";
        }
        else
        {
            $course->updated_at = Date('Y-m-d H:i:s');
            $message = "Course ".$data['name']."has been updated";
        }

        $course->name = $data['name'];
        $course->description = $data['description'];
        
        #Assigning staff to a course (Staff In charge/Class Teacher)
        if($data['staff'] != '')
        {
            $course->staffInCharge = $data['staff'];
        }
        foreach($request['subjectName'] as $key=>$subjectName)
        {
            if($subjectName != '')
            {
                $subject = new Subject();

                $subject->name = $subjectName;
                $subject->description = $request['subjectDescription'][$key];
                if($request['staffId'][$key] != '')
                {
                    $subject->staffId = $request['staffId'][$key];
                }
                $subjects[] = $subject;
            }
        }
        
        $course->save();
        $course->subjects()->delete();
        $course->subjects()->saveMany($subjects);
        
        return redirect('/course')->with('success',$message);
    }
}
