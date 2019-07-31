@extends('layouts.top.app')

@section('menu-1','active')

@section('top_script')

<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/all.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css"> 
@stop

@section('content')
<div id="sender">
    <manage-sender :ticket_count="{{ auth()->user()->created_ticket_count() }}" :auth_user="{{ json_encode(auth()->user()) }}"></manage-sender>
    <alert-age-tickets></alert-age-tickets>
</div>

@stop


@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/icheck.min.js"></script> 
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/manage-sender.js') }}"></script>

@stop
