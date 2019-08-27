@sintexlayouttop
 


@slot('page_title')
@yield('title','Helpdesk Philippines')
@endslot


@auth
    @slot('skin',config('app.skin'))
@elseauth
    @slot('skin','skin-black')
@endauth
 


@slot('header_tools')
@yield('header_tools')
@endslot

@slot('nav_brand')
<a href="/" class="navbar-brand">
    <img src="{{ asset('img/brand-icon.png') }}" class="brand-logo">
    <b>Helpdesk</b> Philippines
</a>
@endslot

@slot('navigation')
@include('layouts.project.navigation')
@endslot

@slot('content')
@yield('content')


@auth
<div id="profile">
<edit-profile></edit-profile>
</div>
@endauth

@endslot


@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/circle-image/image.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
@yield('top_script')
@endslot

@slot('end_script')
<script src="http://cdn.sportscity.com.ph/circle-image/image.js"></script>
<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
@yield('bottom_script')

@endslot


@slot('footer')

@include('layouts.project.footer')

@endslot

@endsintexlayouttop