@sintexemail

@slot('content')
Hi {{ $escalated_to }}
<br><br>
This is to inform you that the ticket below was escalated to you by <strong>{{ $escalated_by }}</strong>.
<br>
<br>
@include('mail.template.progress')
<br>
If you wish to <strong>follow up</strong>, <strong>cancel</strong>, <strong>check the status or communicate</strong> with <strong>{{ $ticket->caterer->name }}</strong> you can access the link below to perform your desired action.<br>
<br>
@include('mail.template.ticket_details')

@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
