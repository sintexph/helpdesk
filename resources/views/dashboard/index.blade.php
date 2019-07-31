@extends('layouts.sidebar.app')

@section('menu-3','active')

@section('top_script') 

@stop
@section('content')

<div id="dashboard">
    <dashboard-index></dashboard-index>
</div>
@stop

@section('bottom_script') 
<script src="{{ asset('/js/dashboard.js') }}"></script>
@stop
