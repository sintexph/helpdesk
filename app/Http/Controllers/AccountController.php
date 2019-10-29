<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Ticket;
use DB;

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
        $role=$request['role'];
        $active=$request['active'];

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

        if(!empty($active))
            $accounts->where('active',$active);
            
        if(!empty($role))
            $accounts->where('role',$role);

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

        $user=new User;
        $user->name=strtoupper($request['name']);
        $user->email=$request['email'];
        $user->password=bcrypt($request['password']);
        $user->position=$request['position'];
        $user->username=$request['username'];
        $user->created_by=auth()->user()->name;
                    
        $user->id_number=strtoupper($request['id_number']);
        $user->factory=$request['factory'];
        $user->contact=$request['contact'];
        $user->role=$request['role'];

        $user->active=$request['active']; 
        $user->save();

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

        
        try {
            DB::beginTransaction();

            $account->name=strtoupper($request['name']);
            $account->email=$request['email'];
            $account->position=$request['position'];
            $account->username=$request['username'];
            $account->updated_by=auth()->user()->name;
            $account->active=$request['active'];

            $account->id_number=strtoupper($request['id_number']);
            $account->factory=$request['factory'];
            $account->role=$request['role'];
            $account->contact=$request['contact'];

            
            if(!empty($request['password']))
                if(\Hash::needsRehash($request['password']))
                    $account->password=bcrypt($request['password']);

            if($account->isDirty('email'))
                Ticket::where('sender_email',$account->getOriginal('email'))
                ->update(['sender_email'=>$account->email]);

            $account->save();
            DB::commit();
            

            return response()->json(['message'=>'Account has been successfully updated!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(442,$th->getMessage());
        }
    }
    public function delete($id)
    {
        $account=User::find($id);
        abort_if($account==null,404,'Account could not be found!');
        $account->delete();

        return response()->json(['message'=>'Account has been successfully deleted!']);
    }
}
