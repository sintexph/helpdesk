@sintexemail

@slot('content')


Hi {{ $ticket->sender_name }},
<br><br>
This is to inform you that <strong>{{ $ticket->caterer->name }}</strong> has marked your ticket as solved.<br>
Please have this ticket closed by accessing the link below or if you feel this is not solved yet, then you can also re open the ticket.
<br><br>
<hr>
@include('mail.template.ticket_details')


@endslot
@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
