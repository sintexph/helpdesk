@sintexemail

@slot('content')
Hi {{ $ticket->caterer->name }}
<br><br>
This is to inform you that <strong>{{ $ticket->sender_name }}</strong> has closed the ticket.
<br>
<br>

@if(!empty($ticket->user_rating) || !empty($ticket->user_feedback))
<hr>
    <strong>User Feedback:</strong>&nbsp;
    @if(!empty($ticket->user_rating))

        @for($i=0; $i<$ticket->user_rating; $i++)
        <span style="color:#ffbb00; font-size:25px;">&#11088;</span>
        @endfor

        @for($i=0; $i<(5-$ticket->user_rating); $i++)
        <span style="color:#8c8c8c; font-size:25px;">&#11088;</span>
        @endfor

    @else
        <span style="color:#8c8c8c; font-size:25px;">&#11088;</span>
        <span style="color:#8c8c8c; font-size:25px;">&#11088;</span>
        <span style="color:#8c8c8c; font-size:25px;">&#11088;</span>
        <span style="color:#8c8c8c; font-size:25px;">&#11088;</span>
        <span style="color:#8c8c8c; font-size:25px;">&#11088;</span>
    @endif
    <br>
    @if(!empty($ticket->user_feedback))
    <strong>User Feedback:</strong>&nbsp;{!! nl2br($ticket->user_feedback) !!}
    @endif
<hr>
@endif

@include('mail.template.ticket_details')

@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
