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

                            <tr>

                                <td colspan="2">

                                    @if($project->is_public==false)
                                    <strong><i class="fa fa-lock" aria-hidden="true"></i> Private</strong>
                                    @else
                                    <strong><i class="fa fa-globe" aria-hidden="true"></i>
                                        Public&nbsp;&nbsp;&nbsp;<a
                                            href="{{ route('projects.public_view',$project->id) }}" target="_blank"><i
                                                class="fa fa-link" aria-hidden="true"></i> View public
                                            link</a></strong>
                                    @endif
                                </td>



                            </tr>




                        </tbody>
                    </table>

                </div>


            </div>
            <div class="box-header">

            </div>
            <div class="box-footer">
                <div class="btn-group">
                    <a href="?view=kanban"
                        class="btn btn-default {{ app('request')->input('view')=='kanban' || !app('request')->input('view') ?'active':'' }}">Kanban</a>
                    <a href="?view=table"
                        class="btn btn-default {{ app('request')->input('view')=='table'?'active':'' }}">Task
                        List</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-7">

        <histories ref="histories" project_id="{{ $project->id }}"></histories>


    </div>
</div>
