@sintexlayouttop

@slot('page_title','Ticket # '.$ticket->control_number.' for approval')

@slot('skin','skin-black')

@slot('nav_brand')
<a href="/" class="navbar-brand">
    <img src="{{ asset('img/brand-icon.png') }}" class="brand-logo">
    <b>Helpdesk</b> Philippines
</a>
@endslot

@slot('content')


<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">Ticket #: {{ $ticket->control_number }} | {{ $ticket->title }}</h3>
    </div>
    <div class="box-body">
        @include('layouts.sessions')

        @if($ticket->approved_at==null)
        <form action="{{ route('approval.approve',$ticket->id) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="form-group has-feedback{{ $errors->has('token') ? ' has-error' : '' }}">
                <input type="text" class="form-control" name="token" value="{{ old('token') }}" placeholder="Token"
                    required>
                @if($errors->has('token'))
                <span class="help-block">{{ $errors->first('token') }}</span>
                @endif
            </div>
            <p>Please provide a token that was sent to your email (<a
                    href="mailto:{{ $ticket->approver_email }}">{{ $ticket->approver_email }}</a>)</p>

            <input type="submit" class="btn btn-danger btn-sm" value="Reject Ticket">
            <input type="submit" class="btn btn-success btn-sm" value="Approve Ticket">
        </form>
        @endif
    </div>
</div>

@endslot


@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/ticket.css') }}">

@endslot

@slot('end_script')

@endslot


@slot('footer')

@include('layouts.top.footer')

@endslot

@endsintexlayouttop
