<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('accounts.index');
    }

    /**
     * Get the account information
     * @param $id The database id of the account
     */
    public function fetch($id)
    {
        $account=User::find($id);
        abort_if($account==null,404,'Account could not be found!');

        return json_encode($account);

    }
    public function list(Request $request)
    {
        $find=$request['find'];

        $accounts=User::on();

        if(!empty($find))
        {
            $accounts->where(function($condition)use($find){
                $condition->orWhere('name','like','%'.$find.'%')
                ->orWhere('email','like','%'.$find.'%')
                ->orWhere('id_number','like','%'.$find.'%')
                ->orWhere('position','like','%'.$find.'%')
                ->orWhere('username','like','%'.$find.'%');
            });
        }

        return datatables($accounts)->rawColumns([
            'active',
        ])->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            
            'name'=>'required',
            'email'=>'required|unique:users,email',
            'password'=> ['required', 'string', 'min:6', 'confirmed'],
            'position'=>'required',
            'username'=>'required|unique:users,username', 
            'id_number'=>'required|unique:users,id_number', 
            'factory'=>'required', 
            'contact'=>'required',
            'role'=>'required',
            'active'=>'required',

        ]);

        User::create([
            'name'=>strtoupper($request['name']),
            'email'=>$request['email'],
            'password'=>bcrypt($request['password']),
            'position'=>$request['position'],
            'username'=>$request['username'],
            'created_by'=>auth()->user()->name,
            
            'id_number'=>$request['id_number'],
            'factory'=>$request['factory'],
            'contact'=>$request['contact'],
            'role'=>$request['role'],

            'active'=>$request['active'],
        ]);

        return response()->json(['message'=>'Account has been successfully created!']);
    }
    /**
     * Update the account information
     * @param $request Holds the data to be updated
     * @param $id The database id of the account
     */
    public function update(Request $request,$id)
    {
        $validation=[

            'name'=>'required',
            'email'=>'required|unique:users,email,'.$id,
            
            'position'=>'required',
            'username'=>'required|unique:users,username,'.$id,
            'id_number'=>'required|unique:users,id_number,'.$id,
            'factory'=>'required', 
            'contact'=>'required',
            'role'=>'required',
            'active'=>'required',

        ];

        # Validate password if there has a value
        if(!empty($request['password']))
            $validation['password']=['required', 'string', 'min:6', 'confirmed'];

        $this->validate($request,$validation);

        $account=User::find($id);
        abort_if($account==null,404,'Account could not be found!');

        
        $account->name=strtoupper($request['name']);
        $account->email=$request['email'];
        $account->position=$request['position'];
        $account->username=$request['username'];
        $account->updated_by=auth()->user()->name;
        $account->active=$request['active'];

        $account->id_number=$request['id_number'];
        $account->factory=$request['factory'];
        $account->role=$request['role'];
        $account->contact=$request['contact'];

        
        if(!empty($request['password']))
            if(\Hash::needsRehash($request['password']))
                $account->password=bcrypt($request['password']);

        $account->save();
        

        return response()->json(['message'=>'Account has been successfully updated!']);
    }
    public function delete($id)
    {
        $account=User::find($id);
        abort_if($account==null,404,'Account could not be found!');
        $account->delete();

        return response()->json(['message'=>'Account has been successfully deleted!']);
    }
}
