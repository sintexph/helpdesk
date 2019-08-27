<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\User;
use App\Factory;
use State;
use DB;
use App\Setting;
use App\CannedSolution;
use App\Project;

class UtilityController extends Controller
{
    public function suggestion_sender_name()
    {
        $result[]=[
            'name'=>'Kevin Loquencio'
        ];
        return json_encode($result);
    }
    public function suggestion_sender_email()
    {
        $result[]=[
            'email'=>'kevin.loquencio@sportscity.com.ph'
        ];
        return json_encode($result);
    }

    public function categories()
    {
        $categories=Category::all();
        return json_encode($categories);
    }
    
    public function factories()
    {
        $factories=Factory::all();
        return json_encode($factories);
    }  
    public function get_users()
    {
        return json_encode(User::all());
    }

    public function find_user(Request $request)
    {
        $q=$request['q'];
        $users=User::where(function($condition)use($q){
            $condition->orWhere('name','like','%'.$q.'%');
        })->limit(10)->get();
        
        return json_encode($users);
    }
    
    public function get_auth_user()
    {
        return json_encode(auth()->user());
    }

    public function get_applications()
    {
        return json_encode(\App\CustomRequest::all());
    }

    public function unclosed_tickets()
    { 
        $aged_setting=Setting::where('name','AGED_TICKETS')->first();
        $tickets=auth()->user()->tickets()->select(['control_number','id','title','created_at','state'])
        ->where('state','=',State::SOLVED)->get();

        $tickets=$tickets->filter(function($ticket)use($aged_setting){ 

            $cd=\Carbon\Carbon::now();
            $dc=new \Carbon\Carbon($ticket->created_at);

            # Get the day difference between two dates
            $day_diff=(int)($cd->diffInSeconds($dc)/86400); 

            # return if the day difference is bigger or equals to the aged settings value
            if($day_diff>=(int)$aged_setting->value)
                return $ticket; 
        });
        

        return json_encode($tickets);
    }

    public function setting(Request $request)
    {
        $this->validate($request,['name'=>'required']);
        $setting=Setting::where('name',$request->name)->first();
        return response()->json($setting->value);
    }


    public function canned_solutions(Request $request)
    {
        $q=$request['q'];
        $canned_solutions=CannedSolution::select([
            'id',
            'name as text',
        ])
        ->where(function($condition)use($q){
            $condition->orWhere('name','like','%'.$q.'%');
        })->limit(10)->get()->makeHidden('content_html');
        
        return json_encode($canned_solutions);
    }
    public function canned_solution($id)
    {
        $canned_solutions=CannedSolution::find($id);
        return json_encode($canned_solutions);
    }

    /**
     * PROJECTS CREATED AND FOLLOWED BY THE AUTHENTICATED USER
     * 
     */
    public function projects()
    {
        $user=auth()->user();
        $projects=Project::whereNotIn('state',[State::CANCELLED,State::CLOSED])
        ->select(['id','name'])
        ->where(function($query)use($user){
            $query->orWhere('created_by',$user->id)
            ->orWhereJsonContains('followers',$user->id);
        });
 
        return json_encode($projects->get());
    }

    public function current_date()
    {
        return json_encode(\Carbon\Carbon::now()->format("Y-m-d"));
    }
}