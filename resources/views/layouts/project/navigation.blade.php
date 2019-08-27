<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li class="@yield('menu-1')">
            <a href="{{ route('projects') }}">
                <i class="fa fa-clipboard" aria-hidden="true"></i> <span>Projects</span>
            </a>
        </li>
        <li class="dropdown @yield('menu-2')">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tasks"
                    aria-hidden="true"></i> Tasks</a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="{{ route('tasks') }}?view=kanban">Kanban</a><span class="fa fa-th-large pull-right"></span>
                </li>
                <li>
                    <a href="{{ route('tasks') }}?view=table">List</a><span class="fa fa-th-list"
                        aria-hidden="true"></span>
                </li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('img/brand-icon.png') }}" class="nav-logo">
                {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu">
                <li><a href="/">Tickets</a><span class="glyphicon glyphicon-book pull-right"></span></li>
                <li><a href="/">Manage</a><span class="glyphicon glyphicon-cog pull-right"></span></li>
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
