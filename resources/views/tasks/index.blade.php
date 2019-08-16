@extends('layouts.sidebar.app')

@section('menu-7','active')

@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
@stop
@section('content')

<div id="tasks">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="{{ app('request')->input('view')=='kanban' || !app('request')->input('view') ?'active':'' }}"><a
                    href="?view=kanban">Kanban</a>
            </li>
            <li class="{{ app('request')->input('view')=='table'?'active':'' }}"><a href="?view=table">Task List</a>
            </li>
            <li class="pull-right"> 
                <a href="#" @click.prevent="$refs.addTask.show()" class="text-muted">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Task
                </a>
            </li>
        </ul>
    </div>
        <div class="box box-solid">
        <div class="box-body" style="min-height:70px;">
            <task-progress></task-progress>
        </div>
    </div>
    

    @if(app('request')->input('view')=='kanban' || !app('request')->input('view'))
    <kanban-tasks ref="kanbanTask"></kanban-tasks>
    @elseif(app('request')->input('view')=='table')
    <task-list ref="taskList"></task-list>
    @endif

    <add-task ref="addTask"></add-task>
</div>



@stop

@section('bottom_script')


<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/tasks.js') }}"></script>

@stop
