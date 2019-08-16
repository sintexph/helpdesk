<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use DB;
use App\Project;

class TaskReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('report.tasks');
    }


    public function list(Request $request)
    {  
        $filter_from=$request->from;
        $filter_to=$request->to;
        $filter_user=$request->user;
        $filter_date=$request->date;
        $filter_find=$request->find;
        
        $tasks=Task::on();

        if(!empty($filter_user))
            $tasks->where('assigned_to',$filter_user);

        if(!empty($filter_to) && !empty($filter_from))
        {
            switch (strtolower($filter_date)) {
                case 'progress':

                        $tasks->where(function($query)use($filter_from,$filter_to){
                            $query->orWhereRaw(DB::raw("date(start_date) between '".$filter_from."' and '".$filter_from."' "))
                            ->orWhereRaw(DB::raw("date(due_date) between '".$filter_from."' and '".$filter_from."' "));
                        });

                    break;
                
                default:
                        $tasks->whereRaw(DB::raw("date(created_at) between '".$filter_from."' and '".$filter_from."' "));
                    break;
            }
        }
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
            },
            'creator'=>function($query){
                $query->select(['id','name']);
            },
        ]);

        return datatables($tasks)->rawColumns(['description_html'])->toJson();
    }

    public function download(Request $request)
    {
        $filter_from=$request->from;
        $filter_to=$request->to;
        $filter_user=$request->user;
        $filter_date=$request->date;
        $filter_find=$request->find;
        
        $tasks=Task::on();

        if(!empty($filter_user))
            $tasks->where('assigned_to',$filter_user);

        if(!empty($filter_to) && !empty($filter_from))
        {
            switch (strtolower($filter_date)) {
                case 'progress':

                        $tasks->where(function($query)use($filter_from,$filter_to){
                            $query->orWhereRaw(DB::raw("date(start_date) between '".$filter_from."' and '".$filter_from."' "))
                            ->orWhereRaw(DB::raw("date(due_date) between '".$filter_from."' and '".$filter_from."' "));
                        });

                    break;
                
                default:
                        $tasks->whereRaw(DB::raw("date(created_at) between '".$filter_from."' and '".$filter_from."' "));
                    break;
            }
        }
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
            },
            'creator'=>function($query){
                $query->select(['id','name']);
            },
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
            'PROJECT_ID', 
            'ASSIGNED_TO', 
            'START_DATE', 
            'DUE_DATE', 
            'LABEL', 
            'STATE', 
            'PRIORITY',
            'CREATED_BY', 
            'CREATED_AT',
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
                $task->creator->name, 
                $task->created_at, 
            ]);
        }
    }
}
