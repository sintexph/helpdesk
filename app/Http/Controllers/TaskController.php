<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Helpers\TaskHelper;
use App\User;
use App\Task;
use State;
use App\TaskAttachment;
use App\Helpers\UploadHelper;
use DB;

class TaskController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tasks.index');
    }
    public function save(Request $request)
    {
        $this->validate($request,[
            'project_id'=>'nullable',
            'name'=>'required',
            'description'=>'required',
            'assigned_to'=>'nullable',
            'start_date'=>'nullable',
            'due_date'=>'nullable',
            'label'=>'required',
            'priority'=>'required',
            'state'=>'required',
            'attachments_input'=>'nullable',
            'remarks'=>'nullable|string',
        ]);

        $project=Project::find($request->project_id); 
        $assigned_to=User::find($request->assigned_to);

  
      
        try {
            DB::beginTransaction();

            $task=TaskHelper::save(
                $request->name,
                $request->description,
                $assigned_to,
                $request->start_date,
                $request->due_date,
                $request->label,
                $request->state,
                $request->priority,
                $request->remarks,
                $project
            );
 

            # Upload the attachments
            if(!empty($request->attachments_input))
            {
                foreach($request->attachments_input as $attachment)
                {
                    $file_upload=UploadHelper::upload_file($attachment,auth()->user()->name);
                    TaskAttachment::create([
                        'file_upload_id'=>$file_upload->id,
                        'task_id'=>$task->id,
                    ]);
                }
            }


            DB::commit();

            return response()->json(['message'=>'Task has been successfully created!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[ 
            'name'=>'required',
            'project_id'=>'nullable',
            'description'=>'required',
            'assigned_to'=>'nullable',
            'start_date'=>'nullable',
            'due_date'=>'nullable',
            'label'=>'required',
            'priority'=>'required',
            'state'=>'required',
            'attachments'=>'nullable',
            'attachments_input'=>'nullable',
            'remarks'=>'nullable|string',
        ]);
 
        $task=Task::find($id);
        abort_if($task==null,404,'Task could not be found!'); 

        $project=Project::find($request->project_id); 
        $assigned_to=User::find($request->assigned_to);
  
        try {
    
            DB::beginTransaction();
            $task=TaskHelper::update(
                $task,
                $request->name,
                $request->description,
                $assigned_to,
                $request->start_date,
                $request->due_date,
                $request->label,
                $request->state,
                $request->priority,
                $request->remarks,
                $project
            );
    
            if(!empty($request->attachments_input))
            {
                # Upload the attachments
                foreach($request->attachments_input as $upload)
                {
                    $file_upload=UploadHelper::upload_file($upload,auth()->user()->name);
                    TaskAttachment::create([
                        'file_upload_id'=>$file_upload->id,
                        'task_id'=>$task->id,
                    ]);
                }
            }
    

            # Check if there are to be deleted with the uploaded attachments
            $to_be_deleted_attachments=explode(",",$request->to_be_deleted_attachments);
            if(!empty($to_be_deleted_attachments))
            {
                
                foreach($to_be_deleted_attachments as $attachment_id)
                {
                    $attachment=TaskAttachment::find($attachment_id);
                    if(!empty($attachment))
                        $attachment->delete();
                }
            }

            \App\Helpers\ProjectHelper::auto_state($task->project);

            DB::commit();


            return response()->json(['message'=>'Task has been successfully updated!']);


        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function kanban(Request $request)
    {
        $project=Project::find($request->project_id);
        $filter_state=$request->filter_state;
        $filter_label=$request->filter_label;
        $filter_find=$request->filter_find;
        
        $tasks=Task::on();
        if(!empty($project))
            $tasks->where('project_id',$project->id);

        if(!empty($filter_state))
            $tasks->where('state',$filter_state);
        if(!empty($filter_label))
            $tasks->where('label',$filter_label);


        if(!empty($filter_find))
            $tasks->where(function($query)use($filter_find){
                $query->orWhere('name','like','%'.$filter_find.'%')
                ->orWhere('description','like','%'.$filter_find.'%');
            });

        $tasks->with(['project'=>function($query){
            $query->select(['id','name']);
        }]);

        return json_encode($tasks->get());
    }

    public function list(Request $request)
    {
        $project=Project::find($request->project_id);
        $filter_state=$request->state;
        $filter_find=$request->find;
        $filter_label=$request->label;
        
        $tasks=Task::on();
        if(!empty($project))
            $tasks->where('project_id',$project->id);

        if(!empty($filter_state))
            $tasks->where('state',$filter_state);
        if(!empty($filter_label))
            $tasks->where('label',$filter_label);

        if(!empty($filter_find))
            $tasks->where(function($query)use($filter_find){
                $query->orWhere('name','like','%'.$filter_find.'%')
                ->orWhere('description','like','%'.$filter_find.'%');
            });
            
        $tasks->orderBy('state','asc');

        $tasks->with([
            'project'=>function($query){
                $query->select(['id','name']);
            },
            'assignee'=>function($query){
                $query->select(['id','name']);
            }
        ]);

        return datatables($tasks)->toJson();
    }

    public function download(Request $request)
    {
        $project=Project::find($request->project_id);
        $filter_state=$request->state;
        $filter_find=$request->find;
        $filter_label=$request->label;
        
        $tasks=Task::on();
        if(!empty($project))
            $tasks->where('project_id',$project->id);

        if(!empty($filter_state))
            $tasks->where('state',$filter_state);
        if(!empty($filter_label))
            $tasks->where('label',$filter_label);

        if(!empty($filter_find))
            $tasks->where(function($query)use($filter_find){
                $query->orWhere('name','like','%'.$filter_find.'%')
                ->orWhere('description','like','%'.$filter_find.'%');
            });
            
        $tasks->orderBy('state','asc');

        $tasks->with([
            'project'=>function($query){
                $query->select(['id','name']);
            },
            'assignee'=>function($query){
                $query->select(['id','name']);
            }
        ]);

        $tasks=$tasks->get();


        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=task-list.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        fputcsv($output,[
            'NAME', 
            'DESCRIPTION', 
            'PROJECT ID', 
            'ASSIGNED TO', 
            'START DATE', 
            'DUE DATE', 
            'LABEL', 
            'STATE', 
            'PRIORITY',
            'REMARKS',
            'CREATED BY', 
            'CREATED AT',
        ]);

        foreach($tasks as $task)
        { 
            fputcsv($output,[
                $task->name, 
                $task->description, 
                $task->project!=null?$task->project->name:'',
                $task->assignee->name, 
                $task->start_date, 
                $task->due_date, 
                $task->label, 
                $task->state_text, 
                $task->priority_text,
                $task->remarks==null?'':$task->remarks,
                $task->creator->name, 
                $task->created_at, 
            ]);
        }
    }


    public function update_state(Request $request,$id)
    {
        $task=Task::find($id);

        try {
    
            DB::beginTransaction();
             
            $task->state=$request->state;
            $task->save();

            \App\Helpers\ProjectHelper::auto_state($task->project);

            DB::commit();


            return response()->json([]);


        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function delete($id)
    {
        $task=Task::find($id);
        \abort_if($task==null,404,'Task could not be found!');
        $task->delete();

        return response()->json(['message'=>'Task has been successfully deleted!']);
    }

    public function cancel($id)
    {
        $task=Task::find($id);
        \abort_if($task==null,404,'Task could not be found!');
        \abort_if($task->state==State::CANCELLED,400,'Task has been cancelled already!');

        $task->state=State::CANCELLED;
        $task->save();

        return response()->json(['message'=>'Task has been successfully cancelled!']);
    }

    public function info($id)
    {
        $task=Task::with('project')
        ->with('creator')
        ->with('assignee')
        ->with(['attachments'=>function($query){
            $query->select(['*'])->with('file_upload');
        }])
        ->find($id);
        \abort_if($task==null,404,'Task could not be found!');

        return json_encode($task);  
    }
    public function progress(Request $request)
    {
        $project=Project::find($request->project_id);
        
        return json_encode(TaskHelper::progress($project));   
    }
}
