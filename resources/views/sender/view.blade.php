@extends('layouts.top.app')

@section('top_script')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/all.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>

<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

</script>

@stop

@section('content')

<div id="sender">

    <div class="row">
        <div class="col-sm-8">
            <div class="status {{ str_replace(' ','-',strtolower($ticket->state_text)) }}">{{ $ticket->state_text }}
            </div>

            @if($ticket->cancelled_at!=null)
            <div class="status {{ strtolower($ticket->state_text) }}">
                Ticket was cancelled by <strong>{{ $ticket->cancelled_by }}</strong> last
                {{ $ticket->cancelled_at }}
                due to {{ $ticket->cancellation_reason }}
            </div>
            @endif

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Ticket #: {{ $ticket->control_number }} - {{ $ticket->title }}</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <dl class="dl-custom">
                                <dt>Sender</dt>
                                <dd>{{ $ticket->sender_name }}</dd>
                                <dt>Email</dt>
                                <dd>{{ $ticket->sender_email }}</dd>
                                <dt>Date</dt>
                                <dd>{{ $ticket->created_at }}</dd>
                                <dt>Factory</dt>
                                <dd>{{ $ticket->sender_factory }}</dd>
                                <dt>Phone/Extension</dt>
                                <dd>{{ $ticket->sender_phone }}</dd>
                            </dl>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <dl class="dl-custom">
                                <dt>IP Address</dt>
                                <dd>{{ $ticket->sender_internet_protocol_address }}</dd>
                                <dt>Urgency</dt>
                                <dd>
                                    @switch($ticket->urgency)
                                    @case('1')
                                    <a title="Low Priority"><i class="fa fa-flag priority-1" aria-hidden="true"></i>
                                        Low</a>
                                    @break
                                    @case('2')
                                    <a title="Normal Priority"><i class="fa fa-flag priority-2" ria-hidden="true"></i>
                                        Normal</a>
                                    @break
                                    @case('3')
                                    <a title="High Priority"><i class="fa fa-flag priority-3" aria-hidden="true"></i>
                                        High</a>
                                    @break
                                    @endswitch
                                </dd>
                                <dt>Carbon Copies</dt>
                                <dd>
                                    @if(!empty($ticket->sender_carbon_copies))
                                    @foreach($ticket->sender_carbon_copies as $cc)
                                    <div class="label label-primary">{{ $cc }}</div>
                                    @endforeach
                                    @endif
                                </dd>
                            </dl>
                        </div>
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


                <div class="box-header">
                    <h3 class="box-title">Ticket Content</h3>
                    <div class="pull-right">
                        <a href="{{ route('content.download',$ticket->control_number) }}" title="Download this ticket"
                            class="btn btn-xs btn-default">
                            <i aria-hidden="true" class="fa fa-download"></i> Download
                        </a>
                    </div>

                </div>
                <div class="box-body">
                    <iframe frameborder="0" scrolling="no" onload="resizeIframe(this)"
                        src="{{ route('content.view',[ $ticket->control_number,$ticket->token]) }}"
                        width="100%"></iframe>
                </div>

            </div>

            <ticket-conversations token="{{ $ticket->token }}" ticket_id="{{ $ticket->id }}">
            </ticket-conversations>
        </div>
        <div class="col-sm-4">
            @if($ticket->state!=State::CLOSED && $ticket->state!=State::PENDING &&
            $ticket->state!=State::CANCELLED && $ticket->sender_email==auth()->user()->email)
            <div class="box box-solid">
                <div class=" box-header with-border">
                    <h3 class="box-title">Actions</h3>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        @if($ticket->state==State::SOLVED)
                        <li>
                            <open-ticket ticket_id="{{ $ticket->id }}">
                            </open-ticket>
                        </li>
                        <li>
                            <a href="#" @click.prevent="$refs.closeTicket.show()">
                                <i class="fa fa-ticket" aria-hidden="true"></i>
                                <span>Close Ticket</span>
                            </a>
                        </li>
                        @endif
                        @if($ticket->state!=State::SOLVED)
                        <li>
                            <cancel-ticket ticket_id="{{ $ticket->id }}">
                            </cancel-ticket>
                        </li>
                        @endif
                    </ul>
                    @if($ticket->state==State::SOLVED)
                    <close-ticket ticket_id="{{ $ticket->id }}" ref="closeTicket">
                    </close-ticket>
                    @endif
                </div>
            </div>
            @endif
            <div class="box box-solid">
                @if($ticket->catered_at!=null)
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
                @endif
                <div class=" box-header with-border">
                    <h3 class="box-title">User's Feedback</h3>
                </div>
                <div class="box-body">
                    <ticket-rating :rating="{{ json_encode($ticket->user_rating ) }}"
                        :feedback="{{ json_encode( $ticket->user_feedback) }}">
                    </ticket-rating>
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Ticket Progress</h3>
                </div>
                <div class="box-body">
                    <ticket-progress :ticket_id="{{ $ticket->id }}"></ticket-progress>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/icheck.min.js"></script>
<script src="{{ asset('js/scroll_to.js') }}"></script>
<script src="{{ asset('js/manage-sender.js') }}"></script>

@if(!empty(app('request')->input('scroll')))
<script>
    scroll_to("#{{app('request')->input('scroll')}}");

</script>
@endif


@stop
