@sintexemail

@slot('content')
Hi {{ $ticket->caterer->name }}
<br><br>
This is to inform you that <strong>{{ $ticket->sender_name }}</strong> has closed the ticket.
<br>
<br>
<hr>
@include('mail.template.ticket_details')

@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail

