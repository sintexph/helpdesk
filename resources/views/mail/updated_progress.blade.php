@sintexemail

@slot('content')
Hi {{ $ticket->sender_name }}
<br><br>
Kindly see below your updated ticket progress.
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
