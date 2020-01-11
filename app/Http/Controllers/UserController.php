<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserStore;
use App\User;
use App\Role;
use App\Course;
use Carbon\Traits\Date;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * List the users present in our system
     */
    public function index()
    {
        $users = User::all();

        $page['title'] = "Users List";
        return view('user.index',compact('users','page'));
    }

    /**
     * Load the form for creation of user
     */
    public function create()
    {
        $user = new User();
        $roles = Role::all();
        $courses = Course::where('organisationId',Auth::user()->organisationId)->get();

        return view('user.form',compact('user','roles','courses'));
    }

    /**
     * Load the form to update the information of requested user
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $userRoles = collect($user->roles())->toArray();
        $courses = Course::where('organisationId',Auth::user()->organisationId)->get();

        return view('user.form',compact('user','roles','userRoles','courses'));
    }

    /**
     * Create the user/update user details
     */
    public function store(UserStore $request)
    {
        $data = $request->validated();
        
        $user = User::find($data['id']);
        # Check if the user ID is present, if present the retrieve the user data. Else create a new user.
        if(is_null($user))
        {
            $user = new User();
            $message = 'User profile for <strong>'.$user->firstName.' '.$user->lastName.'</strong> has been created';
            $user->password = Hash::make(rand(11111111,99999999));
            if(Auth::user()->organisationId != null)
            {
                $user->organisationId = Auth::user()->organisationId;
            }
        }
        else
        {
            $message = 'User profile of <strong>'.$user->firstName.' '.$user->lastName.'</strong> has been updated';
            if($data['password'] != '')
            {
                $user->password = Hash::make($data['password']);
            }
        }

        if($data['course'] != '')
        {
            $user->courseId = $data['course'];
        }

       $user->firstName = $data['firstName'];
       $user->lastName = $data['lastName'];
       $user->dateOfBirth = Date('Y-m-d',strtotime($data['dateOfBirth']));
       $user->email = $data['email'];
       $user->street = $data['street'];
       $user->country = $data['country'];
       $user->city = $data['city'];
       $user->pinCode = $data['postalCode'];
       
       
       if(!is_null($request->file('profilePic')))
       {
           $user->profilePic = $request->file('profilePic')->getClientOriginalName();
       }

       $user->save();
       #Add/update roles to the user
       $user->roles()->sync([$data['roles']]);

       #Upload the file into a directory
       if(!is_null($request->file('profilePic')))
       {
        $request->file('profilePic')->storeAs('public/user/avatar/'.$user->id,$request->file('profilePic')->getClientOriginalName());
       }
       
       return redirect('user')->with('success', $message);
    }

    public function teacherIndex()
    {

    }

    /**
     * List all the students belonging to the logged in users organisation
     */
    public function studentIndex()
    {
       $users = User::whereHas('roles', function($q){
        $q->where('role_user.role_id',4);
       })->where('organisationId',Auth::user()->organisationId)->get();

       $page['title'] = "Students List";
       return view('user.index',compact('users','students','page'));
    }

    /** 
     * Create a student
     */
    public function studentCreate()
    {
        $user = new User();
        $roles = Role::all();
        $userRoles = collect($user->roles())->toArray();
        $defaultRole = 4;

        
        return view('user.form',compact('user','roles','userRoles','defaultRole'));
    }
}
