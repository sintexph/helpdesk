<?php

namespace App\Helpers;
 
use App\Task;
use App\User;
use App\Project;
use DB;
use App\Helpers\ProjectHelper;
use App\Helpers\ProjectHistoryHelper;

class TaskHelper 
{
    /**
     * SAVE
     */
    public static function save(
        $name,
        $description,
        User $assigned_to=null,
        $start_date,
        $due_date,
        $label,
        $state,
        $priority,
        $remarks,
        Project $project=null
    )
    {
        $task=new Task;
        $task->name=$name;
        $task->description=$description;

        # Assigned automatically to the user who created the task
        if($assigned_to==null)
            $task->assigned_to=auth()->user()->id;
        else
            $task->assigned_to=$assigned_to->id;

        $task->start_date=$start_date;
        $task->due_date=$due_date;
        $task->label=$label;
        $task->state=$state;
        $task->priority=$priority;
        $task->remarks=$remarks;

        if($project!=null)
            $task->project_id=$project->id;
        
        $task->created_by=auth()->user()->id;
        $task->save();

        if($task->project!=null)
            ProjectHistoryHelper::task_create($task,auth()->user());# Save history
    

        return $task;
    }

    public static function update(
        Task $task,
        $name,
        $description,
        User $assigned_to=null,
        $start_date=null,
        $due_date=null,
        $label,
        $state,
        $priority,
        $remarks,
        Project $project=null
    )
    {
        $task->name=$name;
        $task->description=$description;

        # Assigned automatically to the user who updated the task
        if($assigned_to==null)
            $task->assigned_to=auth()->user()->id;
        else
            $task->assigned_to=$assigned_to->id;

        $task->start_date=$start_date;
        $task->due_date=$due_date;
        $task->label=$label;
        $task->state=$state;
        $task->priority=$priority;
        $task->remarks=$remarks;

        if($project!=null)
            $task->project_id=$project->id;
        else
            $task->project_id=null;

        $task->save();

        if($task->project!=null)
            ProjectHistoryHelper::task_update($task,auth()->user()); # Save history

        return $task;

    }


    public static function progress(Project $project=null)
    {
        $completed_task=Task::on();
        $incomplete_task=Task::on();
        $tasks=Task::on();

        if($project!=null)
        {
            $completed_task->where('project_id',$project->id);
            $incomplete_task->where('project_id',$project->id);
            $tasks->where('project_id',$project->id);
        }
 

        $completed_task->where('state',State::COMPLETED);
        $incomplete_task->whereNotIn('state',[State::COMPLETED,State::CANCELLED]);

        $tasks->where('state','<>',State::CANCELLED);

        return [
            'complete'=>$completed_task->count(),
            'incomplete'=>$incomplete_task->count(),
            'total'=>$tasks->count(),
        ];
    }

    public static function completion_rate(Project $project=null)
    {
        $completed_task=Task::on();
        $tasks=Task::on();

        if($project!=null)
        {
            $completed_task->where('project_id',$project->id);
            $tasks->where('project_id',$project->id);
        }
 

        
        $completed_task->where('state',State::COMPLETED); 
        $tasks->where('state','<>',State::CANCELLED);

        return $tasks->count()==0?0:round(($completed_task->count()/$tasks->count())*100,0);
    }
}
