<table class="table" style="width:100%;">
    <tbody style="margin-bottom:20x;">
        @if(!empty($ticket->catered_by))
        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Catered By</td>
            <td>: {{ $ticket->caterer->name }}</td>
        </tr>
        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Email</td>
            <td>: <a href="#">{{ $ticket->caterer->email }}</a>
            </td>
        </tr>
        @endif
        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Ticket No.</td>
            <td>: <strong>{{ $ticket->control_number }}</strong></td>
        </tr>

        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Sender Name</td>
            <td>: {{ $ticket->sender_name }}</td>
        </tr>
        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Sender Email</td>
            <td>: {{ $ticket->sender_email }}</td>
        </tr>
        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Date</td>
            <td>: {{ $ticket->created_at }}</td>
        </tr>
        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Factory</td>
            <td>: {{ $ticket->sender_factory }}</td>
        </tr>
        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Urgency</th>
            <td>: {{ $ticket->urgency_text }}</td>
        </tr>

        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Title</th>
            <td>: {{ $ticket->title }}</td>
        </tr>

        <tr>
            <td style="padding-right:10px;white-space: nowrap; width: 1%;">
                Url</th>
            <td>:
                @if(isset($url))
                <a href="{{ $url }}">{{ $url }}</a>
                @else
                <a href="{{ route('user.view',$ticket->id) }}">
                    {{ route('user.view',$ticket->id) }}
                </a>
                @endif
            </td>
        </tr>

    </tbody>
</table>
