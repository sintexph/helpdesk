@extends('layouts.top.app')

@section('content')
<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">{{ __('Reset Password') }}</h3>
    </div>

    <div class="box-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        {{ route('password.email') }}
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="control-label">Email Address</label>

                <div class="input-group">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                </div>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <button type="submit" class="btn btn-sm btn-primary">
                {{ __('Send Password Reset Link') }}
            </button>
            <a href="{{ route('login') }}" class="btn btn-sm btn-success">
                {{ __('Login') }}
            </a>
        </form>
    </div>
</div>
@endsection
