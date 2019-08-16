<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Ticket;
use DB;
use Illuminate\Support\Facades\Input;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('manage.settings.index');
    }

    public function list()
    {
        $find=Input::get('find');
        $settings=Setting::on();

        if(!empty($find))
            $settings->where('name','like','%'.$find.'%');
        return datatables($settings)->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:settings',
            'value'=>'required',
            'remark'=>'nullable',
        ]);

        Setting::create([
            'name'=>$request['name'],
            'value'=>$request['value'],
            'remark'=>$request['remark'],
            'created_by'=>auth()->user()->name,
        ]);

        
        return response()->json(['message'=>'Setting has been successfully saved!']);
    }
    public function info($id)
    {
        $setting=Setting::find($id);
        abort_if($setting==null,404,'Setting could not be found!');
        return json_encode($setting);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required|unique:settings,name,'.$id,
            'value'=>'required',
            'remark'=>'nullable',
        ]);

        $setting=Setting::find($id);
        abort_if($setting==null,404,'Setting could not be found!');

        try {
         

            $setting->name=$request['name'];
            $setting->value=$request['value'];
            $setting->remark=$request['remark'];
            
            $setting->updated_by=auth()->user()->name;

            $setting->save();

            DB::commit();
            return response()->json(['message'=>'Setting has been successfully saved!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function delete($id)
    { 
        $setting=Setting::find($id);
        abort_if($setting==null,404,'Setting could not be found!'); 

        $setting->delete();

        return response()->json(['message'=>'Setting has been successfully deleted!']);
    }
}
