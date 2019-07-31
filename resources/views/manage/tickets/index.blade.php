@extends('layouts.sidebar.app')

@section('menu-1','active')

@section('title','Ticket List')

@section('header_title','Ticket List')
@section('header_title_sm','All tickets that is un-catered and catered by your account')


@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Manage Document</li>
@stop


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/all.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">

@stop
@section('content')
<div id="manage-ticket">
    <ticket-list></ticket-list>
</div>
@stop

@section('bottom_script')


<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script> 
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/icheck.min.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/manage-tickets.js') }}"></script>
@stop
