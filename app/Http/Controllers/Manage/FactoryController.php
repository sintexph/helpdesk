<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factory;
use App\Ticket;
use DB;

class FactoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('manage.factories.index');
    }

    public function list()
    {
        $factories=Factory::on();
        return datatables($factories)->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:factories',
        ]);

        Factory::create([
            'name'=>$request['name'],
            'created_by'=>auth()->user()->name,
        ]);

        
        return response()->json(['message'=>'Factory has been successfully saved!']);
    }
    public function info($id)
    {
        $factory=Factory::find($id);
        abort_if($factory==null,404,'Factory could not be found!');
        return json_encode($factory);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required|unique:factories,name,'.$id,
        ]);

        $factory=Factory::find($id);
        abort_if($factory==null,404,'Factory could not be found!');

        try {
            Ticket::where('factory',$factory->name)->update([
                'factory'=>$request['name'],
            ]);

            $factory->name=$request['name'];
            $factory->updated_by=auth()->user()->name;

            $factory->save();

            DB::commit();
            return response()->json(['message'=>'Factory has been successfully saved!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function delete($id)
    { 
        $factory=Factory::find($id);
        abort_if($factory==null,404,'Factory could not be found!'); 

        $factory->delete();

        return response()->json(['message'=>'Factory has been successfully deleted!']);
    }
}
