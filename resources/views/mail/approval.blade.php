@sintexemail

@slot('content')
Hi {{ $ticket->approver_name }}
<br><br>
We are sending this email to you to get an approval for an account request that was sent by {{ $ticket->sender_name }}.
<br><br>
Please access the link below to view the details.<br>
<a href="{{ $url }}">{{ $url }}</a>
<br>
<br>
Use this authentication code to approve/reject the request.<br>
<strong>Authentication Code</strong>: <span style="font-size:15px;">{{ $ticket->token }}</span> <br>
<br>

Q: <span style="color:#f0941d;">How to approve/reject?</span> <br>
A: <span style="color:#209413;">Access the link above and look for "Approve Request" button and just provide the authentication code.</span>
<br>


<br>
<br>
<hr>
@include('mail.template.ticket_details')
<br>
<hr>
<p style="font-size:15px; font-weight: 700;">Content</p>
<br>
{!! $ticket->content !!}

@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
