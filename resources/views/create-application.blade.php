@extends('layouts.sidebar.app')

@section('top_script')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/all.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>
@stop
@section('content')
<div id="home">
    <application-form></application-form>
</div>
@stop

@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/icheck.min.js"></script>
<script src="{{ asset('js/home.js') }}"></script>
@stop