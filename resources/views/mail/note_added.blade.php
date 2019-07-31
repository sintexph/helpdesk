@sintexemail

@slot('content')
Hi {{ $ticket->caterer->name }}
<br><br>
This is to inform you that <strong>{{ $ticket_note->created_by }}</strong> has added a note to your catered request.
<br>
<br>
<blockquote style="padding: 10px 20px; margin: 0 0 20px; font-size: 15px; border-left: 5px solid #eee;">
{!! nl2br($ticket_note->content) !!}
</blockquote>
<hr>
@include('mail.template.ticket_details')

@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
