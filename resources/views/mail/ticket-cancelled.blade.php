@sintexemail

@slot('content')
Hi {{ $receiver_name }}
<br><br>
This is to inform you that <strong>{{ $ticket->cancelled_by }}</strong> has cancelled your ticket.<br>
<br>
<hr>

@include('mail.template.ticket_details')


@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
