@extends('layouts.sidebar.app')

@section('menu-1','active')

@section('title','View Ticket#: '.$ticket->control_number.' | '.$ticket->title)

@section('header_title','Ticket#: '.$ticket->control_number)
@section('header_title_sm',$ticket->title)


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>
@stop
@section('content')
<div id="manage-ticket">
    @include('manage.tickets.view_src.main')
</div>
@stop

@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/manage-tickets.js') }}"></script>
@stop
