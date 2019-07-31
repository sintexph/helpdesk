@extends('layouts.top.app')

@section('top_script')

<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
@stop


@section('content')

<div id="auth">
    <register-form></register-form>
</div>

@endsection

@section('bottom_script')
   <script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>

<script src="{{ asset('js/auth.js') }}"></script>
@stop
