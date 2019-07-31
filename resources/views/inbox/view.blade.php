@extends('layouts.top.app')


<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/all.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>



@section('content')

<div class="box box-solid">
    <div class="box-body">
        <strong>Ticket #: <a title="{{ $ticket->title }}" href="{{ route('user.view',[$ticket->control_number,$ticket->token]) }}">{{ $ticket->control_number }}</a> 
            | {{ $ticket->title }} - <a title="Click here to view the full ticket" href="{{ route('user.view',[$ticket->control_number,$ticket->token]) }}">View</a></strong>
            
        <div>
            {{ $ticket->sender_name }} - <a href="#">{{ $ticket->sender_email }}</a>
        </div>
    </div>
    
</div>

<div id="conversation">
    <ticket-conversations token="{{ $ticket->token }}" ticket_id="{{ $ticket->id }}"></ticket-conversations>
</div>

@endsection



@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/icheck.min.js"></script>
<script src="{{ asset('js/conversation.js') }}"></script>

@stop
