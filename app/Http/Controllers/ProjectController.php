<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Ticket;
use DB;
use App\User;
use App\Helpers\TaskHelper;
use Illuminate\Support\Facades\Input;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('projects.index');
    }

    public function list(Request $request)
    {
        $find=$request->find;
        $state=$request->state;

        $projects=Project::on();

        if(!empty($find))
            $projects->where('name','like','%'.$find.'%');

        if(!empty($state))
            $projects->where('state',$state);

        $projects->orderBy('state','asc');
            
        
        $projects->with('creator');

        return datatables($projects)->toJson();
    }
    public function download(Request $request)
    {
        $find=$request->find;
        $state=$request->state;

        $projects=Project::on();

        if(!empty($find))
            $projects->where('name','like','%'.$find.'%');

        if(!empty($state))
            $projects->where('state',$state);

        $projects=$projects->orderBy('state','asc')->get();

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=project-list.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        fputcsv($output,[
            'NAME', 
            'DESCRIPTION', 
            'REQUESTED BY',
            'START DATE', 
            'DUE DATE', 
            'FOLLOWERS', 
            'TAGS', 
            'STATE',
            'CREATED BY',
            'CREATED AT', 
        ]);

        foreach($projects as $project)
        { 
            fputcsv($output,[
                $project->name, 
                $project->description, 
                $project->requestor->name,
                $project->start_date, 
                $project->due_date, 
                implode(",",$project->followers), 
                implode(",",$project->tags),
                $project->state_text,
                $project->creator->name,
                $project->created_at, 
            ]);
        }
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:projects',
            'description'=>'required',
            'requested_by'=>'required',
            'start_date'=>'nullable',
            'due_date'=>'nullable',
            'followers'=>'nullable',
            'tags'=>'nullable',
            'state'=>'required',
        
        ]);

        $requestor=User::find($request->requested_by);
        abort_if($requestor==null,400,'Could not find the requestor on the database!');


        Project::create([
            'name'=>$request->name, 
            'description'=>$request->description, 
            'requested_by'=>$requestor->id,
            'start_date'=>$request->start_date, 
            'due_date'=>$request->due_date, 
            'followers'=>$request->followers, 
            'tags'=>$request->tags, 
            'state'=>$request->state, 
            'created_by'=>auth()->user()->id,
        ]);

        
        return response()->json(['message'=>'Project has been successfully saved!']);
    }
    public function info($id)
    {
        $project=Project::find($id);
        abort_if($project==null,404,'Project could not be found!');
        
        return json_encode($project);
    }

    public function view($id)
    {
        $project=Project::find($id);
        abort_if($project==null,404,'Project could not be found!');
        return view('projects.view',['project'=>$project]);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required|unique:projects,name,'.$id,
            'description'=>'required',
            'requested_by'=>'required',
            'start_date'=>'nullable',
            'due_date'=>'nullable',
            'followers'=>'nullable',
            'tags'=>'nullable',
            'state'=>'required',
        
        ]);


        $project=Project::find($id);
        abort_if($project==null,404,'Project could not be found!');
        
        $requestor=User::find($request->requested_by);
        abort_if($requestor==null,400,'Could not find the requestor on the database!');


        try {
            

            $project->name=$request->name; 
            $project->description=$request->description; 
            $project->requested_by=$requestor->id;
            $project->start_date=$request->start_date; 
            $project->due_date=$request->due_date; 
            $project->followers=$request->followers; 
            $project->tags=$request->tags; 
            $project->state=$request->state;
 
            $project->save();

            DB::commit();
            return response()->json(['message'=>'Project has been successfully updated!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function delete($id)
    { 
        $project=Project::find($id);
        abort_if($project==null,404,'Project could not be found!'); 

        $project->delete();

        return response()->json(['message'=>'Project has been successfully deleted!']);
    }
 
}
