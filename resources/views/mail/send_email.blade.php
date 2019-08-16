@sintexemail

@slot('content')

{!! $content !!}


<br>
<br><br>
<strong>{{ $user->name }}</strong> - {{ $user->position }}<br>
<span style="color:#292929;">{{ $user->email }}</span>
<br><br>
<hr>
@include('mail.template.ticket_details')

@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
