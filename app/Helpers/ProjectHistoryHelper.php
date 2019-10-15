<?php

namespace App\Helpers;
use App\Project;
use App\Helpers\State;
use App\Helpers\Urgency;
use App\Task;
use App\ProjectHistory;
use App\User;

class ProjectHistoryHelper 
{
    const PROJECT_NOTE=1;
    const TASK_UPDATE=2;
    const TASK_MOVED=3;
    const CHANGED_STATE=4;
    const DELETE_TASK=5;
    const TASK_CREATION=6;
    const TASK_CANCELLATION=7;

    public static function icon($state_value)
    {
        $icon='';
        switch ($state_value) {
            case static::PROJECT_NOTE:
                $icon='fa fa-sticky-note bg-yellow';
            break;
            case static::TASK_UPDATE: 
                $icon='fa fa-pencil bg-yellow';
            break;
            case static::TASK_MOVED: 
                $icon='fa fa-location-arrow bg-green';
            break;
            case static::CHANGED_STATE: 
                $icon='fa fa-exchange bg-blue';
            break;
            case static::DELETE_TASK:
                $icon='fa fa-trash bg-red';
            break;
            case static::TASK_CREATION:
                $icon='fa fa-plus bg-blue';
            break;
            case static::TASK_CANCELLATION:
                $icon='fa fa-ban bg-red';
            break;
        }

        return $icon;

    }

    public static function type($state_value) {
        
        $stateClass = new \ReflectionClass ( 'App\Helpers\ProjectHistoryHelper' );
        $constants = $stateClass->getConstants();

        $constName = null;
        foreach ( $constants as $name => $value )
        {
            if ( $value == $state_value )
            {
                $constName = $name;
                break;
            }
        }

        $constName=strtolower($constName);
        $constName=\str_replace("_"," ",$constName);

        return ucwords(strtolower($constName));
    }
    

    /**
     * This is must be created before saving the task
     * @param $project Project, where the task belong
     * @param $task The actual task
     * @param $user Who will execute the update
     */
    public static function task_update(Task $task,User $user)
    {
        if($task->isDirty())
        {
            $updated_fields=$task->getDirty();
            $original_fields=$task->getOriginal();

            # Get all the updated fields
            foreach ($updated_fields as $field => $value) {

                # Create an instance of the history
                $history=new ProjectHistory;

                $history->type=static::TASK_UPDATE;


                # Customizing history description on each field
                switch($field)
                {
                    case 'name':

                        $history->description=$user->name.' has updated the name of the task from '.\strtolower($original_fields[$field]).' to '.\strtolower($value);

                    break;

                    case 'description':

                        $history->description=$user->name.' has updated the description of the task('.$task->name.') to '.\strtolower($value);

                    break;
                    case 'assigned_to': 

                        $new_assignee=User::find($value);
                        $history->description=$user->name.' has change the task('.$task->name.') assignee to '.$new_assignee->name;

                    break;

                    case 'start_date':
                        $history->description=$user->name.' has changed the task('.$task->name.') start date to '.$value;
                    break;

                    case 'due_date': 
                        $history->description=$user->name.' has changed the task('.$task->name.') due date to '.$value;
                    break;

                    case 'label': 
                        $history->description=$user->name.' has changed the task('.$task->name.') label to '.$value;
                    break;
                    case 'state': 
                        $state=State::state($value);
                        $history->description=$user->name.' has changed the task('.$task->name.') state to '.strtolower($state);
                    break; 
                    case 'priority': 

                        $urgency=Urgency::urgency($value);
                        $history->description=$user->name.' has changed the task('.$task->name.') priority to '.strtolower($urgency);

                    break;
                    case 'remarks':

                        $history->description=$user->name.' has updated the task('.$task->name.') remarks to '.$value;

                    break;
                }
                

                if($field=='project_id')
                {
                    $old_project=Project::find($original_fields[$field]);
                    $new_project=Project::find($value);

                    static::task_move($task,$old_project,$new_project,$user);
                }
                else
                {
                    $history->project_id=$task->project->id;
                    $history->field=strtoupper(str_replace("_"," ",$field));
                    $history->creator=$user->name;
                    $history->save();
                }




            }
        }
    }

    public static function task_move(Task $task,Project $old_project,Project $new_project,User $user)
    {
        $description=$user->name.' has moved the task '.$task->name.' from '.strtolower($old_project->name).' to '.strtolower($new_project->name);
        $old_project_history=new ProjectHistory;

        $old_project_history->type=static::TASK_MOVED;
        $old_project_history->description=$description;
        $old_project_history->field='PROJECT';
        $old_project_history->creator=$user->name;
        $old_project_history->project_id=$old_project->id;
        $old_project_history->save();

        $new_project_history=new ProjectHistory;
        $new_project_history->type=static::TASK_MOVED;
        $new_project_history->description=$description;
        $new_project_history->field='PROJECT';
        $new_project_history->creator=$user->name;
        $new_project_history->project_id=$new_project->id;
        $new_project_history->save();
        
        return $task;
    }

    public static function task_change_state(Task $task,User $user,$state)
    {
        $state=State::state($state);

        $project_history=new ProjectHistory;
        $project_history->type=static::CHANGED_STATE;
        $project_history->description=$user->name.' has changed the task('.$task->name.') state to '.strtolower($state);
        $project_history->field='STATE';
        $project_history->creator=$user->name;
        $project_history->project_id=$task->project->id;
        $project_history->save();

        return $project_history;
    }

    /**
     * EXECUTE FIRST BEFORE DELETING THE TASK
     */
    public static function task_delete(Task $task,User $user)
    {
      

        $project_history=new ProjectHistory;
        $project_history->type=static::DELETE_TASK;
        $project_history->description=$user->name.' has deleted the task, '.strtolower($task->name);
        $project_history->creator=$user->name;
        $project_history->project_id=$task->project->id;
        $project_history->save();

        return $project_history;
    }

    public static function task_create(Task $task,User $user)
    {
        $project_history=new ProjectHistory;
        $project_history->type=static::TASK_CREATION;
        $project_history->description=$user->name.' has created a task, '.strtolower($task->name);
        $project_history->creator=$user->name;
        $project_history->project_id=$task->project->id;
        $project_history->save();

        return $project_history;
    }

    public static function task_cancel(Task $task,User $user)
    {
        $project_history=new ProjectHistory;
        $project_history->type=static::TASK_CANCELLATION;
        $project_history->description=$user->name.' has cancelled the task, '.strtolower($task->name);
        $project_history->creator=$user->name;
        $project_history->project_id=$task->project->id;
        $project_history->save();

        return $project_history;
    }

}
