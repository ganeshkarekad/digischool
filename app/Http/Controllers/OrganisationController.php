<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organisation;
use App\Http\Requests\OrganisationStore;
use Carbon\Traits\Date;
use App\User;
use App\Config;

class OrganisationController extends Controller
{
    /**
     * List all organisations
     */
    public function index()
    {
        $organisations = Organisation::all();

        return view('organisation.index',compact('organisations'));
    }

    /**
     *  Load the form to add an Organisation
     */
    public function create()
    {
        $organisation = new Organisation();
        
        #Get all the administrators (people eligible to be associated with the organisation)
        $users = User::whereHas('roles', function($q){
            $q->where('role_id','2');
        })->get();

        return view('organisation.form',compact('organisation','users'));
    }

    public function edit($id)
    {
        $organisation = Organisation::find($id);
        $config = Config::where('organisationId',$id)->pluck('value','name');

        #Get all the administrators (people eligible to be associated with the organisation)
        $users = User::whereHas('roles', function($q){
            $q->where('role_id','2');
        })->get();

        if(is_null($organisation))
        {
            return back()->with('error','The Organisation that you\'re trying to access does not exist');
        }
        return view('organisation.form',compact('organisation','users','config'));
    }

    /**
     * Store Organisation details
     */
    public function store(OrganisationStore $request)
    {
        $data = $request->validated();
        
        $organisation = Organisation::find($data['id']);

        if(is_null($organisation))
        {
            $organisation = new Organisation();
            $organisation->created_at = Date('Y-m-d H:i:s');

            $message = 'Organisation '.$data['name'].' has been added';
        }     
        else{
            $organisation->updated_at = Date('Y-m-d H:i:s');
            $message = 'Organisation '.$data['name'].' has been updated';
        } 


        $organisation->name = $data['name'];
        $organisation->shortDescription = $data['shortDescription'];
        $organisation->description = $data['description'];

        if($request->file('logo') != null)
        {
            $organisation->logo = $request->file('logo')->getClientOriginalName();
        }

        if(!is_null($data['user']))
        {
            $user = User::find($data['user']);
            $user->organisationId = $organisation->id;
        }
        


        $organisation->save();
        $user->save();
        $config = [
            '_OPENING_TIME_' => $data['_OPENING_TIME_'],
            '_CLOSING_TIME_' => $data['_CLOSING_TIME_'],
            '_CLASS_DURATION_' => $data['_CLASS_DURATION_'],
            '_LUNCH_BREAK_START_TIME_' => $data['_LUNCH_BREAK_START_TIME_'],
            '_LUNCH_BREAK_END_TIME_' => $data['_LUNCH_BREAK_END_TIME_'],
            '_BREAK_DURATION_' => $data['_BREAK_DURATION_'],
            '_NUMBER_OF_BREAK_' => $data['_NUMBER_OF_BREAK_']
        ];
        foreach($config as $key => $value)
        {
            $config = Config::where('organisationId',$organisation->id)->where('name',$key)->first();

            if(is_null($config))
            {
                $config = new Config;
                $config->created_at = Date('Y-m-d');
            }
            else
            {
                $config->updated_at = Date('Y-m-d');
            }
            
            $config->name = $key;
            $config->value = $value;
            $config->organisatioNId = $organisation->id;
            $config->save();
        }

        if($request->file('logo') != null)
        {
            $request->file('logo')->storeAs('public/organisation/'.$organisation->id,$request->file('logo')->getClientOriginalName());
        }
        

        return redirect('organisation')->with('success', $message);
    }
}
