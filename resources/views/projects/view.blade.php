@extends('layouts.project.app')

@section('header-title','View '.$project->name)
@section('header-title-sm','Details and tasks')

@section('menu-1','active')

@section('title','Project - '.$project->name)

@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@stop
@section('content')


<div id="projects">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="{{ !app('request')->input('tab')?'active':'' }}"><a href="{{ route('projects.view',$project->id) }}"><i aria-hidden="true" class="fa fa-clipboard"></i> {{ $project->name }}</a></li>
            <li class="{{ app('request')->input('tab')=='tasks'?'active':'' }}"><a href="?tab=tasks">Tasks</a></li>
            <li class="{{ app('request')->input('tab')=='att'?'active':'' }}"><a href="?tab=att">Attachments</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Actions <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li role="presentation"><a role="menuitem" @click.prevent="$refs.createNote.show()" href="#"><i
                                class="fa fa-sticky-note" aria-hidden="true"></i> Add Note</a></li>
                    <li role="presentation"><a role="menuitem" @click.prevent="$refs.editProject.show()" href="#"><i
                                aria-hidden="true" class="fa fa-pencil text-yellow"></i> Edit Project</a></li>
                    <li role="presentation"><a role="menuitem" @click.prevent="delete_project('{{ $project->id }}')"
                            href="#"> <i class="fa fa-trash text-red" aria-hidden="true"></i> Delete Project</a></li>

                </ul>
            </li>
        </ul>
    </div>

    @switch(app('request')->input('tab'))
        @case('tasks')
            @include('projects.tabs.tasks')
        @break
        @case('att')
            @include('projects.tabs.attachments')
        @break
        @default
            @include('projects.tabs.main')
        @break
    @endswitch
    <edit-project project_id="{{ $project->id }}" ref="editProject"></edit-project>
    <create-note @added="$refs.histories.list()" project_id="{{ $project->id }}" ref="createNote"></create-note>

</div>


@stop

@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js">
</script>
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/projects.js') }}"></script>

<script>
    GlobalEvent.$on('project-deleted', function () {
        location.replace("/projects")
    });
</script>

@stop
