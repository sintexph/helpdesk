<?php

namespace App\Helpers;
use App\Project;
use App\Helpers\State;

class ProjectHelper 
{

    /**
     * GENERATE STATE BASED ON THE TASK STATES
     */
    public static function generate_state(Project $project)
    {
        $count_pending=$project->tasks->where('state',State::PENDING)->count();
        $count_processing=$project->tasks->where('state',State::PROCESSING)->count();
        $count_completed=$project->tasks->where('state',State::COMPLETED)->count();
        $count_hold=$project->tasks->where('state',State::HOLD)->count();

        if($count_processing!=0)
            return State::PROCESSING;
        if($count_pending!=0)
            return State::PENDING;
        elseif($count_completed!=0)
            return State::COMPLETED;
        elseif($count_hold!=0)
            return State::HOLD;
        else
            return State::PENDING;
    }

    
    public static function auto_state(Project $project)
    {
        $project->state=static::generate_state($project);
        $project->save();

        return $project;
    }

}
