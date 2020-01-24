<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="{{ empty(app('request')->input('view'))?'active':'' }}"><a href="?view=">Ticket Details</a></li>
        <li class="{{ app('request')->input('view')=='convo'?'active':'' }}"><a href="?view=convo">
                Conversation
                @if($ticket->conversations->count()>0)
                <span class="label label-danger">{{ $ticket->conversations->count() }}</span>
                @endif
            </a>
        </li>
        <li class="{{ app('request')->input('view')=='notes'?'active':'' }}"><a href="?view=notes">
                Notes
                @if($ticket->notes->count()>0)
                <span class="label label-danger">{{ $ticket->notes->count() }}</span>
                @endif
            </a>
        </li>

        <li class="{{ app('request')->input('view')=='metrics'?'active':'' }}">
            <a href="?view=metrics">Metrics</a>
        </li>
    </ul>
</div>

@switch(app('request')->input('view'))
@case('notes')
@include('manage.tickets.view_src.tabs.notes')
@break
@case('convo')
@include('manage.tickets.view_src.tabs.conversation')
@break
@case('metrics')
@include('manage.tickets.view_src.tabs.metrics')
@break


@default
@include('manage.tickets.view_src.tabs.default')
@endswitch
