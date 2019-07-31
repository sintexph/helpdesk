

<li class="@yield('menu-3')">
    <a href="{{ route('dashboard') }}">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
    </a>
</li>

<li class="@yield('menu-1')">
    <a href="{{ route('tickets') }}">
        <i class="fa fa-ticket" aria-hidden="true"></i> <span>Requested Tickets</span>
    </a>
</li>


 

@if(auth()->user()->can('admin'))
<li class="@yield('menu-2') treeview">
    <a href="#">
        <i class="fa fa-dashboard"></i> <span>Maintain</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="@yield('menu-2-1')"><a href="{{ route('maintain.application') }}"><i class="fa fa-circle-o"></i>
                Applications</a></li>
        <li class="@yield('menu-2-2')"><a href="{{ route('maintain.factory') }}"><i class="fa fa-circle-o"></i>
                Factories</a></li>
        <li class="@yield('menu-2-3')"><a href="{{ route('maintain.category') }}"><i class="fa fa-circle-o"></i>
                Categories</a></li>
    <li class="@yield('menu-2-4')"><a href="{{ route('maintain.setting') }}"><i class="fa fa-circle-o"></i>
                Settings</a></li>
    </ul>
</li> 


<li class="@yield('menu-4')">
    <a href="{{ route('accounts') }}">
        <i class="fa fa-user" aria-hidden="true"></i> <span>Accounts</span>
    </a>
</li>
<li class="@yield('menu-5')">
    <a href="{{ route('report') }}">
        <i class="fa fa-file" aria-hidden="true"></i> <span>Report</span>
    </a>
</li>

@endif