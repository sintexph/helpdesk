@extends('layouts.project.app')

@section('menu-1','active')

@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
@stop
@section('content')

<div id="projects">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $project->name }} </h3>
            <div class="box-tools pull-right">
                <a title="Edit" href="#" @click.prevent="$refs.editProject.show()" class="btn btn-box-tool"><i
                        aria-hidden="true" class="fa fa-pencil text-yellow"></i> Edit Project</a>

                <a title="Delete" href="#" @click.prevent="delete_project('{{ $project->id }}')"
                    class="btn btn-box-tool">
                    <i class="fa fa-trash text-red" aria-hidden="true"></i> Delete Project</a>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-border table-hover table-striped">
                    <tbody>
                        <tr>
                            <th>Project Name</th>
                            <td><strong><a href="">{{ $project->name }}</a></strong></td>
                            <th>Factory</th>
                            <td>{{ $project->requestor->factory }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{!! nl2br($project->description) !!}</td>


                            <th>Followers</th>
                            <td>
                                @foreach($project->project_followers() as $follower)
                                <a href="mailto:{{ $follower->email }}">{{ $follower->name }}</a><br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>

                            <th>Created By</th>
                            <td>
                                <div>{{ $project->creator->name }}<br><a
                                        href="mailto:{{ $project->creator->email }}">{{ $project->creator->email }}</a>
                                </div>
                                <div>{{ $project->created_at }}</div>
                            </td>

                            <th>Tags</th>
                            <td>
                                @foreach($project->tags as $tag)
                                <span class="label label-primary">{{ $tag }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Requested By</th>
                            <td>
                                {{ $project->requestor->name }}<br>
                                <a href="mailto:{{ $project->requestor->email }}">{{ $project->requestor->email }}</a>
                            </td>

                            <th>Status</th>
                            <td>{{ $project->state_text }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-header">
            @if($project->is_public==false)
            <h3 class="box-title"><i class="fa fa-lock" aria-hidden="true"></i> Private</h3>
            @else
            <h3 class="box-title text-blue"><i class="fa fa-globe" aria-hidden="true"></i> Public&nbsp;&nbsp;&nbsp;<a href="#"><i class="fa fa-link" aria-hidden="true"></i> View public link</a></h3>
            @endif
        </div>
        <div class="box-footer">
            <div class="btn-group">
                <a href="?view=kanban"
                    class="btn btn-default {{ app('request')->input('view')=='kanban' || !app('request')->input('view') ?'active':'' }}">Kanban</a>
                <a href="?view=table"
                    class="btn btn-default {{ app('request')->input('view')=='table'?'active':'' }}">Task List</a>
            </div>
        </div>
    </div>

    @if(app('request')->input('view')=='kanban' || !app('request')->input('view'))
    <kanban-tasks ref="kanbanTask" project_id="{{ $project->id }}"></kanban-tasks>
    @elseif(app('request')->input('view')=='table')
    <task-list ref="taskList" project_id="{{ $project->id }}"></task-list>
    @endif

    <edit-project project_id="{{ $project->id }}" ref="editProject"></edit-project>

</div>
@stop

@section('bottom_script')


<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/projects.js') }}"></script>

@stop
