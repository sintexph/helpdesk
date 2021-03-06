@sintexlayouttop
 


@slot('page_title')
@yield('title','Information Technology Helpdesk')
@endslot


@auth
    @slot('skin',config('app.skin'))
@elseauth
    @slot('skin','skin-red')
@endauth
 


@slot('header_tools')
@yield('header_tools')
@endslot

@slot('nav_brand')
<a href="/" class="navbar-brand">
    <img src="{{ asset('img/brand-icon.png') }}" class="brand-logo">
    <b>SCI Helpdesk</b> Philippines
</a>
@endslot

@slot('navigation')
@include('layouts.top.navigation')
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

<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/circle-image/image.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/venobox/venobox.css">

@yield('top_script')
@endslot

@slot('end_script')
<script src="http://cdn.sportscity.com.ph/circle-image/image.js"></script>
<script src="http://cdn.sportscity.com.ph/venobox/venobox.min.js"></script>
<script src="{{ asset('js/image-viewer.js') }}"></script>
@auth
<script src="{{ asset('js/profile.js') }}"></script>
@endauth

@yield('bottom_script')

@endslot


@slot('footer')

@include('layouts.top.footer')

@endslot

@endsintexlayouttop