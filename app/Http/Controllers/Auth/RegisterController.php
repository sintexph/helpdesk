<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    


    public function showRegistrationForm()
    { 
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validation=[
            'id_number' =>'required|string|unique:users,id_number',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'continue'=>['nullable','boolean'],
        ];

        if($request['continue']==true)
        {
            $validation['factory']=['required','nullable', 'string', 'max:255'];
            $validation['name']=['required','nullable', 'string', 'max:255'];
            $validation['position']=['required','nullable', 'string', 'max:255'];
        }
        
        $this->validate($request,$validation);

        try 
        {


            $password=$request['password'];
            $id_number=$request['id_number'];
            $factory=$request['factory'];
            $position=$request['position'];
            $contact=$request['contact'];
            $name=$request['name'];
            $email=$request['email'];

            if($request['continue']!=true)
            {
                # Check if there are existing employee data from source
                $source_employee=\App\Helpers\DataSourceHelper::find_employee($request['id_number']);
                
                if(empty($source_employee))
                    return response()->json(['continue'=>true],400);
    
                # Get the first data from source
                $source_employee=$source_employee[0];
                $factory=$source_employee->factory;
                $position=$source_employee->position;
                $name=$source_employee->full_name;
            }

            
            
            DB::beginTransaction();

            # Save the user details to the database
            $user=new User;
 
            $user->username=$id_number;
            $user->id_number=$id_number;
            $user->factory=$factory;
            $user->position=$position;
            $user->name=$name;
            $user->email=$email;
            $user->password=\Hash::make($password);
            $user->created_by='Registered';
            $user->save();
            

            $login_details = array(
                'email'     => $email,
                'password'  => $password,
            );

            

            # Login the registered user
            if(\Auth::attempt($login_details))
            {
                DB::commit();
                return response()->json(['message'=>'You have been successfully registered!']);
            }

            else
            {
                DB::rollBack();
                abort(403,'Authentication is not possible.');
            }
                

            
            
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }

    }
    
}
