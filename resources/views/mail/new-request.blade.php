@sintexemail

@slot('content')
Hi {{ $receiver }},
<br><br>
This is to inform you that there is a new <strong>ticket</strong> under <strong>({{ $ticket->sender_factory }})</strong> from
Helpdesk.<br>If you are the assigned Technical Support in the <strong>({{ $ticket->sender_factory }})</strong>, you can access the link below to cater the ticket.
<br><br>
<a href="{{ route('tickets.view',$ticket->id) }}">{{ route('tickets.view',$ticket->id) }}</a>
<br><br>
<hr>
@include('mail.template.ticket_details')

@endslot
@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
