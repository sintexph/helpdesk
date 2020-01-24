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


    <div class="row">
        <div class="col-sm-5">

            <div class="box box-solid">

                <div class="box-body">

                    <div class="table-responsive">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2">Project Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fit">Name</td>
                                    <td>{{ $project->name }}</td>
                                </tr>

                                <tr>
                                    <td class="fit">Description</td>
                                    <td>{{ $project->description_html }}</td>
                                </tr>

                                <tr>
                                    <td class="fit">Created By</td>
                                    <td>
                                        <div>{{ $project->creator->name }}</div>
                                        <div><a
                                                href="mailto:{{ $project->creator->email }}">{{ $project->creator->email }}</a>
                                        </div>
                                        <div>{{ $project->created_at }}</div>
                                    </td>
                                </tr>


                                <tr>
                                    <td class="fit">Requested By</td>
                                    <td>
                                        <div>{{ $project->requestor->name }}</div>
                                        <div><a
                                                href="mailto:{{ $project->requestor->email }}">{{ $project->requestor->email }}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fit">Factory</td>
                                    <td>{{ $project->requestor->factory }}</td>

                                </tr>

                                <tr>
                                    <td class="fit">Followers</td>
                                    <td>
                                        @foreach($project->project_followers() as $follower)
                                        <a href="mailto:{{ $follower->email }}">{{ $follower->name }}</a><br>
                                        @endforeach
                                    </td>


                                </tr>

                                <tr>
                                    <td class="fit">Tags</td>
                                    <td>
                                        @foreach($project->tags as $tag)
                                        <span class="label label-primary">#{{ $tag }}</span>
                                        @endforeach
                                    </td>
                                </tr>


                                <tr>
                                    <td>Status</td>
                                    <td><span
                                            class="status {{ strtolower($project->state_text) }}">{{ $project->state_text }}</span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>Start Date</td>
                                    @if($project->start_date!=null)
                                    <td>{{ $project->start_date->format('F d, Y') }}</td>
                                    @else
                                    <td>-- NA --</td>
                                    @endif

                                </tr>
                                <tr>

                                    <td>Due Date</td>

                                    @if($project->due_date!=null)
                                    <td>{{ $project->due_date->format('F d, Y') }}</td>
                                    @else
                                    <td>-- NA --</td>
                                    @endif


                                </tr>





                            </tbody>
                        </table>

                    </div>




                </div>

            </div>


            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Tasks</h3> <span
                        class="label label-default pull-right">{{ $project->tasks->count() }}</span>
                </div>
                <div class="box-body ">
                    <task-progress project_id="{{ $project->id }}"></task-progress>
                </div>
                <div class="box-body task-list">
                    @foreach($project->tasks()->orderBy('state','asc')->where('state','<>',State::CANCELLED)->get() as $task)
                        @component('projects.src.task')
                        @slot('task',$task)
                        @endcomponent
                    @endforeach
                </div>
            </div>

        </div>
        <div class="col-sm-7">

            <histories ref="histories" project_id="{{ $project->id }}"></histories>
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
