@extends('layouts.top.app')

@section('content')

<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">Helpdesk Account Reset
        </h3>
    </div>
    <div class="helpdesk-landing box-body">

        <div class="row">
            <div class="col-sm-6 col-xs-12 col-md-5 col-lg-4 pull-right">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('Reset Password') }}</h3>
                    </div>

                    <div class="box-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group has-feedback">
                                <label for="email">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="form-control" name="email"
                                    value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                <span class="help-block text-red" role="alert">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group has-feedback">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block text-red" role="alert">
                                    {{ $errors->first('password') }}
                                </span>
                                @endif
                            </div>

                            <div class="form-group ">
                                <label for="password-confirm">{{ __('Confirm Password') }}</label>

                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset Password') }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


@endsection
