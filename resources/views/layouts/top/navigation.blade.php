@auth


<div class="navbar-collapse pull-left collapse" id="navbar-collapse" aria-expanded="false">
    <ul class="nav navbar-nav">
        <li class="@yield('menu-1')"><a href="{{ route('user') }}"><i class="fa fa-ticket" aria-hidden="true"></i>
                Tickets</a></li>
        <li class="@yield('menu-3')"><a href="{{ route('inbox') }}"><i class="fa fa-inbox" aria-hidden="true"></i>
                Inbox</a></li>

        @if(!auth()->user()->can('sender'))
        <li>
            <a href="{{ route('tickets') }}"><i class="fa fa-user-secret" aria-hidden="true"></i> Manage</a>
        </li>
        @endif

    </ul>
</div>


<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ auth()->user()->photo }}" class="nav-logo" alt="User Image">
                <span class="hidden-xs">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a data-toggle="modal" href='#modal-edit-profile'>
                        Profile Settings
                    </a>
                    <span class="glyphicon glyphicon-cog pull-right"></span>
                </li>
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

@endauth
