@sintexemail

@slot('content')
Hi {{ $receiver }},
<br><br>
Your ticket has been successfully created and we are doing our best to cater your concern as soon as possible. Please keep this ticket number (<strong>#{{ ticket_number }}</strong>) for further references and you can access the link below to view the status and communicate with our support staff with regards to your concern. 
<h2>{{ $ticket_number }}</h2>
<a href="{{ route('user.view',[$ticket_number,$ticket_token]) }}">{{ route('user.view',[$ticket_number,$ticket_token]) }}</a>
<hr>
<h4>Ticket Details</h4>
<table style="width:100%;">

    <tbody>
        <tr>
            <th style="padding-right:10px;">Name</th>
            <td style="padding-right:10px;"><a href="#">{{ $sender_name }}</a></td>
        </tr>
        <tr>
            <th style="padding-right:10px;">Email</th>
            <td style="padding-right:10px;"><a href="#">{{ $sender_email }}</a></td>
        </tr>
        <tr>
            <th style="padding-right:10px;">Title</th>
            <td style="padding-right:10px;"><a href="#">{{ $title }}</a></td>
        </tr>
        <tr>
            <th style="padding-right:10px;">Content</th>
            <td style="padding-right:10px;"><a href="#"></a></td>
        </tr>
    </tbody>
</table>
<br>
{!! $content !!}

@endslot
@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
