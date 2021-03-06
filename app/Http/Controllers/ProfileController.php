<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Ticket;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index($username)
    {
        $user=User::where('username',$username)->first();
        abort_if($user==null,404);
        

        return view('profile.index',['user'=>$user]);
    }

    public function update(Request $request)
    {
        $user=auth()->user();

        $validation=[
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$user->id,
            'position'=>'required',
            'username'=>'required|unique:users,username,'.$user->id,
            'id_number'=>'required|unique:users,id_number,'.$user->id,
            'factory'=>'required', 
            'contact'=>'required',
        ];

        # Validate password if there has a value
        if(!empty($request['password']))
            $validation['password']=['required', 'string', 'min:6', 'confirmed'];

        $this->validate($request,$validation);
 
        $user->name=strtoupper($request['name']);
        $user->email=$request['email'];
        $user->position=$request['position'];
        $user->username=$request['username'];
        $user->updated_by=auth()->user()->name;

        $user->id_number=$request['id_number'];
        $user->factory=$request['factory'];
        $user->contact=$request['contact'];


        if($user->isDirty('email'))
            Ticket::where('sender_email',$user->getOriginal('email'))
                ->update(['sender_email'=>$user->email]);

        if(!empty($request['password']))
            if(\Hash::needsRehash($request['password']))
                $user->password=bcrypt($request['password']);

        $user->save();
        

        return response()->json(['message'=>'Account has been successfully updated!']);
    }
}
