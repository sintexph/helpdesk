<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Ticket;
use DB;
use App\User;
use App\Helpers\TaskHelper;
use Illuminate\Support\Facades\Input;
use App\Helpers\UploadHelper;
use App\ProjectAttachment;
use App\ProjectHistory;
use App\Helpers\ProjectHistoryHelper;
use App\Helpers\State;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index');
    }

    private function source(Request $request,$download=false)
    {
        $find=$request->find;
        $state=$request->state;

        $projects=Project::on();
        $projects->where(function($projects){
            $user_id=auth()->user()->id;
            $projects->orWhere('created_by',$user_id)
            ->orWhereJsonContains('followers',$user_id);
        });

        if(!empty($find))
            $projects->where('name','like','%'.$find.'%');

        if($download==false)
        {
            if(!empty($state) && $state!="-1")
            {
                $projects->where('state',$state);
            }
            elseif($state!="-1")
                $projects->whereIn('state',[State::PENDING,State::PROCESSING,State::HOLD]);
        }

            

        if(!$request->order)
        {
            $projects
                ->orderByRaw(DB::raw("case when state=".State::HOLD." then 1 else 0 end"))
                
                ->orderByRaw(DB::raw("case when start_date is null then 1 else 0 end"))
                ->orderBy('start_date','asc');
                
        }
        
        $projects->with('creator');
        $projects->with('requestor');

        return $projects;
    }
    public function list(Request $request)
    {
        $projects=$this->source($request);
        return datatables($projects)->rawColumns([
            'start_date',
            'due_date',
        ])->toJson();
    }
    public function download(Request $request)
    {
        $projects=$this->source($request,true)->get();

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
            'COMPLETION RATE',
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
                $project->completion_rate.'%',
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
            'is_public'=>'required',
            'attachments_input'=>'nullable',
        
        ]);

        $requestor=User::find($request->requested_by);
        abort_if($requestor==null,400,'Could not find the requestor on the database!');

        try {
            DB::beginTransaction();

 

            $project=Project::create([
                'name'=>$request->name, 
                'description'=>$request->description, 
                'requested_by'=>$requestor->id,
                'start_date'=>$request->start_date, 
                'due_date'=>$request->due_date, 
                'followers'=>explode(",",$request->followers), 
                'tags'=>explode(",",$request->tags), 
                'state'=>$request->state, 
                'is_public'=>$request->is_public, 
                'created_by'=>auth()->user()->id,
                ]);

            # Upload the attachments
            if(!empty($request->attachments_input))
            {
                foreach($request->attachments_input as $attachment)
                {
                    $file_upload=UploadHelper::upload_file($attachment,auth()->user()->name);
                    ProjectAttachment::create([
                        'file_upload_id'=>$file_upload->id,
                        'project_id'=>$project->id,
                    ]);
                }
            }


            DB::commit();

            return response()->json(['message'=>'Project has been successfully saved!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }
    public function info($id)
    {
        $project=Project::with(['attachments'=>function($query){
            $query->select(['*'])->with('file_upload');
        }])->find($id);
        
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
            'is_public'=>'required',
            'attachments'=>'nullable',
            'attachments_input'=>'nullable',
        
        ]);


        $project=Project::find($id);
        abort_if($project==null,404,'Project could not be found!');
        
        $requestor=User::find($request->requested_by);
        abort_if($requestor==null,400,'Could not find the requestor on the database!');

  


        try {
            
            DB::beginTransaction();

            $project->name=$request->name; 
            $project->description=$request->description; 
            $project->requested_by=$requestor->id;
            $project->start_date=$request->start_date; 
            $project->due_date=$request->due_date; 
            $project->followers=explode(",",$request->followers); 
            $project->tags=explode(",",$request->tags);
            $project->state=$request->state;
            $project->is_public=$request->is_public;
 
            $project->save();


   
            if(!empty($request->attachments_input))
            {
                # Upload the attachments
                foreach($request->attachments_input as $upload)
                {
                    $file_upload=UploadHelper::upload_file($upload,auth()->user()->name);
                    ProjectAttachment::create([
                        'file_upload_id'=>$file_upload->id,
                        'project_id'=>$project->id,
                    ]);
                }
            }
    

            # Check if there are to be deleted with the uploaded attachments
            $to_be_deleted_attachments=explode(",",$request->to_be_deleted_attachments);
            if(!empty($to_be_deleted_attachments))
            {
                
                foreach($to_be_deleted_attachments as $attachment_id)
                {
                    $attachment=ProjectAttachment::find($attachment_id);
                    if(!empty($attachment))
                        $attachment->delete();
                }
            }



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

    public function add_note(Request $request,$id)
    {
        $this->validate($request,[
            'note'=>'required'
        ]);

        $user=auth()->user();
        $project=Project::find($id);
        abort_if($project==null,404,'Project could not be found!'); 

        $project_history=new ProjectHistory;
        $project_history->type=ProjectHistoryHelper::PROJECT_NOTE;
        $project_history->description=$request->note;
        $project_history->creator=$user->name;
        $project_history->project_id=$project->id;
        $project_history->save();


        return response()->json(['message'=>'Note has been successfully added!']);
    }

    public function public_view(Request $request,$id)
    {

        $project=Project::find($id);
        abort_if($project==null,404,'Project could not be found!');

        # Redirect to a beautiful link
        $slug=strtolower(str_replace(" ","-",$project->name));
        if(!isset($request[$slug]))
            return redirect(route('projects.public_view',$id).'?'.$slug);
        
        
        abort_if($project->is_public==false,403,'Project is not available for viewing.');

        return view('projects.public-view',['project'=>$project]);
    }

}
