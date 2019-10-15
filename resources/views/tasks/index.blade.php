@extends('layouts.project.app')

@section('menu-2','active')

@section('top_script')


<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@stop

@section('content')

<div id="tasks">

    @if(app('request')->input('view')=='kanban' || !app('request')->input('view'))
    <kanban-tasks ref="kanbanTask"></kanban-tasks>
    @elseif(app('request')->input('view')=='table')
    <task-list ref="taskList"></task-list>
    @elseif(app('request')->input('view')=='calendar')
    <task-calendar></task-calendar>
    @endif

 

</div>

@stop


@section('bottom_script')


<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('js/tasks.js') }}"></script>

@stop
