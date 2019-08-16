@extends('layouts.sidebar.app')


@section('menu-2','active')
@section('menu-2-5','active')

@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Manage Canned Solution</li>
@stop

@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">

@stop
@section('content')
<div id="canned-solutions">
    <canned-solution-list></canned-solution-list>
</div>
@stop

@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="http://cdn.sportscity.com.ph/jquery.idle-master/jquery.idle.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/canned-solutions.js') }}"></script>
@stop
