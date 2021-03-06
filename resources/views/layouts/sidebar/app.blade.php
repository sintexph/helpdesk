@sintexlayoutside

@slot('page_title')
@yield('title','Sportscity Helpdesk Philippines')
@endslot

@slot('skin','skin-green')

@slot('nav_brand')
<span class="logo-mini"><b>S</b>HP</span>
<span class="logo-lg"><b>Helpdesk</b> System</span>
@endslot

@slot('navigation')

<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li>
            <a href="{{ route('projects') }}">
                <i class="fa fa-list" aria-hidden="true"></i> <span>Projects</span>
            </a>
        </li>

        <li>
            <a href="{{ route('user') }}">
                <i class="fa fa-ticket" aria-hidden="true"></i> <span>Tickets</span>
            </a>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('img/brand-icon.png') }}" class="nav-logo">
                {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu">
                <li><a href="/">Home</a><span class="glyphicon glyphicon-home pull-right"></span></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign
                        Out</a><span class="glyphicon glyphicon-log-out pull-right"></span>
                    <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</div>

@endslot

@slot('content')
@yield('content')
@endslot

@slot('sidebar')


<div class="user-panel">
    <div class="sintex-circle-image pull-left">
        <a class="venobox" href="{{ auth()->user()->photo }}">
            <img class="attachment-img" src="{{ auth()->user()->photo }}" alt="Attachment Image" />
        </a>
    </div>
    <div class="pull-left info">
        <p>{{ auth()->user()->name }}</p>
        <a href="#">{{ auth()->user()->role_text }}</a>
    </div>
</div>

<ul class="sidebar-menu" data-widget="tree">
    <li class="header">{{ auth()->user()->email }}</li>
    @include('layouts.sidebar.navigation')
</ul>
@endslot



@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/circle-image/image.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/venobox/venobox.css">
@yield('top_script')
@endslot

@slot('end_script')
<script src="http://cdn.sportscity.com.ph/circle-image/image.js"></script>
<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/venobox/venobox.min.js"></script>
<script src="{{ asset('js/image-viewer.js') }}"></script>
@yield('bottom_script')

@endslot


@slot('footer')

@include('layouts.sidebar.footer')

@endslot



@endsintexlayoutside
