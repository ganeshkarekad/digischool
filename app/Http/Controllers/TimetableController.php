<?php

namespace App\Http\Controllers;

use App\Subject;
use Illuminate\Http\Request;
use App\User;
use App\Timetable;
use App\Course;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;

class TimetableController extends Controller
{
    /**
     * Load the calendar with option to manage timetable
     */
    public function index($id)
    {
        $course = Course::find($id);
        $subjects = $course->subjects()->pluck('name','id');
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday','Friday','Saturday'];
        return view('timetable.index',compact('course','subjects','days'));
    }

    public function store($id, Request $request)
    {
        $days = $request->get('subject');
        $subjects = Subject::where('courseId',$id)->get();
        foreach($days as $key=>$day)
        {
            if(!is_null($day[0]))
            {
                $date = new \DateTime() ;
                foreach($day as $subject)
                {
                    $timetable = new Timetable();

                    $timetable->day = $key;
                    $timetable->subjectId = $subject;
                    $timetable->startTime = null;
                    $timetable->endTime = null;
                    $timetable->created_at = $date->format('Y-m-d H:i:s');
                    $timetable->save();
                }
            }
        }
        return redirect('/home');
    }
}
