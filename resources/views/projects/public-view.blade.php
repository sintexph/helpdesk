@sintexlayouttop



@slot('page_title')
@yield('title','Project - '.$project->name)
@endslot

@slot('header_title','View '.$project->name)
@slot('header_title_sm','Details and tasks')

@auth
@slot('skin',config('app.skin'))
@elseauth
@slot('skin','skin-black')
@endauth





@slot('content')

<div id="projects">


    
    @include('projects.tabs.main')

    <div class="row">

        <div class="col-xs-4">
            <div class="box box-solid">
                <div class="box-header with-border bg-yellow">
                    <h3 class="box-title">Pending</h3> <span
                        class="label label-warning pull-right">{{ $project->tasks->where('state',STATE::PENDING)->count() }}</span>
                </div>
                <div class="box-body task-list">
                    <div class="list-group">
                        @foreach($project->tasks->where('state',STATE::PENDING) as $task)
                        @component('projects.src.task')
                        @slot('task',$task)
                        @endcomponent
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="box box-solid">
                <div class="box-header with-border bg-blue">
                    <h3 class="box-title">Processing</h3> <span
                        class="label label-primary pull-right">{{ $project->tasks->where('state',STATE::PROCESSING)->count() }}</span>
                    <!---->
                </div>
                <div class="box-body task-list">
                    <div class="list-group">
                        @foreach($project->tasks->where('state',STATE::PROCESSING) as $task)
                        @component('projects.src.task')
                        @slot('task',$task)
                        @endcomponent
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="box box-solid">
                <div class="box-header with-border bg-green">
                    <h3 class="box-title">Completed</h3> <span
                        class="label label-success pull-right">{{ $project->tasks->where('state',STATE::COMPLETED)->count() }}</span>
                    <!---->
                </div>
                <div class="box-body task-list">
                    <div class="list-group">
                        @foreach($project->tasks->where('state',STATE::COMPLETED) as $task)
                        @component('projects.src.task')
                        @slot('task',$task)
                        @endcomponent
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

 


</div>





@endslot


@slot('start_script')
<link rel="stylesheet" href="{{ asset('css/project.css') }}">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/circle-image/image.css">

@endslot

@slot('end_script')
<script src="http://cdn.sportscity.com.ph/circle-image/image.js"></script>
<script src="{{ asset('js/projects.js') }}"></script>


@endslot


@slot('footer')

@include('layouts.top.footer')

@endslot

@endsintexlayouttop
