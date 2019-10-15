@sintexemail

@slot('content')


Hi {{ $ticket->sender_name }},
<br><br>
This is to inform you that <strong>{{ $ticket->caterer->name }}</strong> has marked your ticket as solved.<br>
Please close the ticket by accessing the link below or if you feel that your ticket has not been solved, you can always re-open the ticket by accessing the link below.
<br><br>
<hr>
@include('mail.template.ticket_details')


@endslot
@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
