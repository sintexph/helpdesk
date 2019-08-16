@extends('layouts.sidebar.app')

@section('menu-5','active')
@section('menu-5-1','active')

@section('title','Ticket Report')
 

 

@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
@stop
@section('content')
<div id="report">
    <report-list></report-list>
</div>
@stop

@section('bottom_script')


<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/ticket-report.js') }}"></script>
@stop
