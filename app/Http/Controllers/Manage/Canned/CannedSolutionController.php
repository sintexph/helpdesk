<?php

namespace App\Http\Controllers\Manage\Canned;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CannedSolution;
use App\Ticket;
use DB;
use Illuminate\Support\Facades\Input;

class CannedSolutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('manage.canned.solutions.index');
    }

    public function list()
    {
        $find=Input::get('find');
        $solutions=CannedSolution::on();

        if(!empty($find))
            $solutions->where('name','like','%'.$find.'%');
        return datatables($solutions)->rawColumns([
            'content_html'
        ])->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:canned_solutions',
            'content'=>'required',
            'type'=>'required',
        ]);

        CannedSolution::create([
            'name'=>$request['name'],
            'content'=>$request['content'],
            'type'=>$request['type'],
            'created_by'=>auth()->user()->name,
        ]);

        
        return response()->json(['message'=>'Canned solution has been successfully saved!']);
    }
    public function info($id)
    {
        $canned_solution=CannedSolution::find($id);
        abort_if($canned_solution==null,404,'Canned solution could not be found!');
        return json_encode($canned_solution);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required|unique:canned_solutions,name,'.$id,
            'content'=>'required',
            'type'=>'required',
        ]);

        $canned_solution=CannedSolution::find($id);
        abort_if($canned_solution==null,404,'Canned solution could not be found!');

        try {
         

            $canned_solution->name=$request['name'];
            $canned_solution->content=$request['content'];
            $canned_solution->type=$request['type'];
            
            $canned_solution->updated_by=auth()->user()->name;

            $canned_solution->save();

            DB::commit();
            return response()->json(['message'=>'Canned solution has been successfully saved!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function delete($id)
    { 
        $canned_solution=CannedSolution::find($id);
        abort_if($canned_solution==null,404,'Canned solution could not be found!'); 

        $canned_solution->delete();

        return response()->json(['message'=>'Canned solution has been successfully deleted!']);
    }
}
