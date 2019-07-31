@sintexemail

@slot('content')

@foreach($ticket->conversations()->orderBy('created_at','desc')->limit('5')->get() as $conversation)
  
    {!! $conversation->content !!}<br>
    <span style="color:#aaaaaa;">{{ $conversation->creator->name }} - {{ $conversation->created_at }}</span><br>
    @if($conversation->attachments->count()!=0)
        <br>
        <strong>Attachments</strong>
        <br>
            @foreach($conversation->attachments as $attachment)
            <a href="{{ $attachment->file_upload->url }}">{{ $attachment->file_upload->file_name }}</a><br>
            @endforeach
        <br>
        <p>If you cannot <strong>download</strong> the file due to some restrictions of your email outlook, just copy the url
            and paste it in your browser.</p>
    @endif
 
    <hr>
 <br><br>
@endforeach


@include('mail.template.ticket_details')
<br>
<p style="color:#fc8888;">Remember to access the link above to reply this email since if you will reply directly to this email, none of our support staff can seen your reply so make sure to use the system in replying.</p>
@endslot

@slot('url',config('app.url'))
@slot('brand','Helpdesk Philippines')

@endsintexemail
