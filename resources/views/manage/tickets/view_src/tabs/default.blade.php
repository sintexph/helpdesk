<div class="row">
    <div class="col-sm-8">
        <div class="status {{ str_replace(' ','-',strtolower($ticket->state_text)) }}">{{ $ticket->state_text }}</div>

        @if($ticket->cancelled_at!=null)
        <div class="status {{ strtolower($ticket->state_text) }}">
            Ticket was cancelled by <strong>{{ $ticket->cancelled_by }}</strong> last
            {{ $ticket->cancelled_at }}
            due to {{ $ticket->cancellation_reason }}
        </div>
        @endif

        <div class="box box-solid">

            <div class="box-body">


                <div class="caterer">
                    <div class="sintex-circle-image circular--landscape">
                        <a class="venobox" href="{{ $ticket->sender->photo }}">
                            <img src="{{ $ticket->sender->photo }}" alt="image alt" />
                        </a>
                    </div>
                    <span class="caterer-name">{{ $ticket->sender->name }}</span>
                    <span class="caterer-position">
                        <a href="mailto:{{  $ticket->sender->email }}">
                            {{ $ticket->sender->email }}&nbsp;&nbsp;&#9744;&nbsp;{{ $ticket->sender->contact }}
                        </a>
                    </span>
                </div>
            </div>
            <div class="box-body">


                <div class="table-responsive">
                    <table class="table no-border table-hover">
                        <tbody>

                            <tr>
                                <td class="fit" colspan="2">
                                    <dl class="dl-custom">
                                        <dt>Ticket#</dt>
                                        <dd><strong><a href="">{{ $ticket->control_number }}</a></strong></dd>
                                        <dt>Title</dt>
                                        <dd>{{ $ticket->title }}</dd>
                                        <dt>Sender</dt>
                                        <dd>{{ $ticket->sender_name }}</dd>
                                        <dt>Email</dt>
                                        <dd>
                                            <a href="mailto:{{ $ticket->sender_email }}">
                                                {{ $ticket->sender_email }}
                                            </a>
                                        </dd>
                                        <dt>Date</dt>
                                        <dd>{{ $ticket->created_at }}</dd>


                                    </dl>
                                </td>
                                <td colspan="4">
                                    <dl class="dl-custom">
                                        <dt>Factory</dt>
                                        <dd>{{ $ticket->sender_factory }}</dd>
                                        <dt>Phone/Extension</dt>
                                        <dd>{{ $ticket->sender_phone }}</dd>
                                        <dt>IP Address</dt>
                                        <dd>{{ $ticket->sender_internet_protocol_address }}</dd>
                                        <dt>Urgency</dt>
                                        <dd>
                                            @switch($ticket->urgency)
                                            @case('1')
                                            <a title="Low Priority"><i class="fa fa-flag priority-1"
                                                    aria-hidden="true"></i>
                                                Low</a>
                                            @break
                                            @case('2')
                                            <a title="Normal Priority"><i class="fa fa-flag priority-2"
                                                    ria-hidden="true"></i>
                                                Normal</a>
                                            @break
                                            @case('3')
                                            <a title="High Priority"><i class="fa fa-flag priority-3"
                                                    aria-hidden="true"></i> High</a>
                                            @break
                                            @endswitch
                                        </dd>
                                        <dt>Carbon Copies</dt>
                                        <dd>
                                            @if(!empty($ticket->sender_carbon_copies))
                                            <table>
                                                <tbody>
                                                    @foreach($ticket->sender_carbon_copies as $cc)
                                                    <tr>
                                                        <td>

                                                            <a class="label label-primary" href="mailto:{{ $cc }}">
                                                                {{ $cc }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endif
                                        </dd>
                                    </dl>
                                </td>
                            </tr>



                            <tr>
                                <th>Catered</th>
                                <th>Processing</th>
                                <th>Solved</th>
                                <th>Closed</th>
                                <th>Hold</th>
                                <th>Cancelled</th>
                            </tr>
                            <tr>
                                <td>
                                    <small>{{ $ticket->catered_at }}</small>
                                    <br><span
                                        class="ht-{{ strtolower($ticket->ht_catered) }}">{{ $ticket->ht_catered }}</span>
                                </td>
                                <td>
                                    <small>{{ $ticket->processing_at }}</small>
                                    <br><span
                                        class="ht-{{ strtolower($ticket->ht_processing) }}">{{ $ticket->ht_processing }}</span>

                                </td>
                                <td>
                                    <small>{{ $ticket->solved_at }}</small>
                                    <br><span
                                        class="ht-{{ strtolower($ticket->ht_solved) }}">{{ $ticket->ht_solved }}</span>

                                </td>
                                <td>
                                    <small>{{ $ticket->closed_at }}</small>
                                    <br><span
                                        class="ht-{{ strtolower($ticket->ht_closed) }}">{{ $ticket->ht_closed }}</span>

                                </td>
                                <td>
                                    {{ $ticket->hold_at }}<br>{{ $ticket->un_hold_at }}
                                </td>
                                <td>{{ $ticket->cancelled_at }}</td>
                            </tr>
                            @if($ticket->references->count()!=0)
                            <tr>
                                <th>Reference Ticket #</th>
                                <th>Title</th>
                                <th>Sender</th>
                                <th>Added By</th>
                                <th colspan="2">Added At</th>
                            </tr>
                            @foreach($ticket->references as $references)
                            <tr>
                                <td class="fit"><a href="{{ route('tickets.view',$references->ticket_reference->id) }}"
                                        target="_blank">{{ $references->ticket_reference->control_number }}</a></td>
                                <td class="fit">{{ $references->ticket_reference->title }}</td>
                                <td class="fit">{{ $references->ticket_reference->sender_name }}</td>
                                <td class="fit">{{ $references->created_by }}</td>
                                <td colspan="2" class="fit">{{ $references->created_at }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>



            </div>

            @if($ticket->notes->count()!=0)
            <div class="box-header with-border">
                <h3 class="box-title">Latest Notes</h3>
            </div>

            <div class="box-body">
                @foreach($ticket->notes()->orderBy('created_at','desc')->limit(3)->get() as $note)
                <blockquote>
                    <p>{{ $note->content }}</p>
                    <small>{{ $note->created_by }}</small>
                </blockquote>
                @endforeach
            </div>
            @endif



            @if($ticket->solved_at!=null)
            <div class="box-header with-border">
                <h3 class="box-title text-green">Solution</h3>
            </div>
            <div class="box-body">
                {!! nl2br($ticket->solution) !!}
            </div>
            @endif

            <div class="box-header with-border">
                <h3 class="box-title">Users Feedback</h3>
            </div>
            <div class="box-body">
                <div style="font-size:20px;">
                    @component('layouts.rating')
                    @slot('rating',$ticket->user_rating)
                    @endcomponent
                </div>
                @if(!empty($ticket->user_feedback))
                {{ $ticket->user_feedback }}
                @else
                <span>No feedback</span>
                @endif
            </div>
        </div>

        <div class="box box-solid">
            <div class=" box-header with-border">
                <h3 class="box-title">Ticket Content</h3>
                <div class="pull-right">
                    <a href="{{ route('content.download',$ticket->control_number) }}" title="Download this ticket"
                        class="btn btn-xs btn-default">
                        <i aria-hidden="true" class="fa fa-download"></i> Download
                    </a>
                </div>
            </div>
            <div class="box-body">
                <iframe src="{{ route('content.view',[ $ticket->control_number,$ticket->token]) }}" width="100%"
                    height="450"></iframe>
            </div>


            @if(!empty($ticket->attachments))

            <div class="box-footer">
                <ul class="mailbox-attachments clearfix">
                    @foreach($ticket->attachments as $attachment)
                    <li>
                        <span class="mailbox-attachment-icon">
                            <i class="fa fa-file" aria-hidden="true"></i>
                        </span>

                        <div class="mailbox-attachment-info">
                            <a href="{{ $attachment->file_upload->url }}" class="mailbox-attachment-name">
                                <i class="fa fa-paperclip"></i>
                                {{ $attachment->file_upload->file_name }}
                            </a>
                            <span class="mailbox-attachment-size">
                                {{ $attachment->file_upload->file_size }}
                                <a href="{{ $attachment->file_upload->url }}"
                                    class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

    </div>
    <div class="col-sm-4">
        @if($ticket->catered_at!=null)
        <div class="box box-solid">
            <div class=" box-header with-border">
                <h3 class="box-title">Catered By</h3>
            </div>
            <div class="box-body">
                <div class="caterer">

                    <div class="sintex-circle-image">
                        <a class="venobox" href="{{ $ticket->caterer->photo }}">
                            <img src="{{ $ticket->caterer->photo }}" alt="image alt" />
                        </a>
                    </div>

                    <span class="caterer-name">{{ $ticket->caterer->name }}</span>
                    <span class="caterer-position">{{ $ticket->caterer->email }}</span>
                </div>
            </div>


        </div>
        @endif

        @if($ticket->approved_at)
        <div class="box box-solid box-success">
            <div class=" box-header with-border">
                <h3 class="box-title"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Approved By</h3>
            </div>
            <div class="box-body">
                <div class="caterer">
                    <span class="caterer-name">{{ $ticket->approver_name}}</span>
                    <span class="caterer-position">{{ $ticket->approver_email}}</span>
                </div>
            </div>
        </div>
        @endif



        @if($ticket->state!=State::CLOSED)
        <ticket-actions :can_update="{{ auth()->user()->can('update',$ticket)?'true':'false' }}"
            :ticket="{{ $ticket }}"></ticket-actions>
        @endif

        <div class="box box-solid">
            <div class=" box-header with-border">
                <h3 class="box-title">Progress</h3>
            </div>
            <div class="box-body">
                <ticket-progress :ticket_id="{{ $ticket->id }}"></ticket-progress>
            </div>
        </div>
    </div>

</div>
