<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CustomRequest;
use App\Helpers\ApplicationHelper;
use Illuminate\Support\Facades\Input;

class ApplicationSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('manage.application.index');
    }

    public function list()
    {
        $find=Input::get('find');
        $applications=CustomRequest::on();

        if(!empty($find))
            $applications->where('name','like','%'.$find.'%');

        
        return datatables($applications)->rawColumns(['fields'])->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:custom_requests',
            'applications'=>'required',
            'fields'=>'required',

            'field_sender_name'=>'string|nullable',
            'field_sender_email'=>'string|nullable',
            'field_sender_factory'=>'string|nullable',
            'field_sender_phone'=>'string|nullable',

        ]);

        CustomRequest::create([
            'name'=>$request['name'],
            'applications'=>ApplicationHelper::generalize_keys($request['applications']),
            'fields'=>ApplicationHelper::generalize_keys($request['fields']),
            'field_sender_name'=>$request['field_sender_name'],
            'field_sender_email'=>$request['field_sender_email'],
            'field_sender_factory'=>$request['field_sender_factory'],
            'field_sender_phone'=>$request['field_sender_phone'],
        ]);

        
        return response()->json(['message'=>'Application has been successfully saved!']);
    }
    public function info($id)
    {
        $application=CustomRequest::find($id);
        abort_if($application==null,404,'Application could not be found!');
        return json_encode($application);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required|unique:custom_requests,name,'.$id,
            'applications'=>'required',
            'fields'=>'required',

            'field_sender_id_number'=>'string|nullable',
            'field_sender_name'=>'string|nullable',
            'field_sender_email'=>'string|nullable',
            'field_sender_factory'=>'string|nullable',
            'field_sender_phone'=>'string|nullable',

        ]);

        $application=CustomRequest::find($id);
        abort_if($application==null,404,'Application could not be found!');

        $application->name=$request['name'];
        $application->applications=ApplicationHelper::generalize_keys($request['applications']);
        $application->fields=ApplicationHelper::generalize_keys($request['fields']);

        $application->field_sender_id_number=$request['field_sender_id_number'];
        $application->field_sender_name=$request['field_sender_name'];
        $application->field_sender_email=$request['field_sender_email'];
        $application->field_sender_factory=$request['field_sender_factory'];
        $application->field_sender_phone=$request['field_sender_phone'];

        $application->save();

        return response()->json(['message'=>'Application has been successfully saved!']);
    }
    public function format_update(Request $request,$id)
    {
        $this->validate($request,[
            'format'=>'required',
        ]);

        $application=CustomRequest::find($id);
        abort_if($application==null,404,'Application could not be found!');

        $application->format=$request['format'];
        $application->save();

        return response()->json(['message'=>'Application format has been successfully saved!']);
    }

    


    public function delete($id)
    { 
        $application=CustomRequest::find($id);
        abort_if($application==null,404,'Application could not be found!'); 

        $application->delete();

        return response()->json(['message'=>'Application has been successfully deleted!']);
    }
}
