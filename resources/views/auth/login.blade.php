@extends('layouts.top.app')


@section('top_script')

<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
@stop


@section('content')




<div id="auth">
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title"><img src="/img/brand-icon.png" style="width:30px;"
                    class="brand-logo">&nbsp;&nbsp;&nbsp;Welcome to SCI Helpdesk Philippines
            </h3>
        </div>
        <div class="helpdesk-landing box-body">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pull-right">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title">Support Team</h3>
    </div>
    <div class="box-body">
        <div class="row">
            @foreach ($supports->chunk(2) as $chunk_sup)
            <div class="col-sm-6">
                <ul class="products-list product-list-in-box">
                    @foreach($chunk_sup as $support)
                    <li class="item">
                        <div class="product-img">
                            <img src="{{ $support->photo }}"
                                onerror="this.src='http://cdn.sportscity.com.ph/images/fallback.png'" alt="User Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">{{ $support->name }}</a>
                            <span class="product-description">
                                {{ $support->position }} - {{ $support->contact }} / Factory:
                                {{ $support->factory }}<br>
                                <a href="mailto:{{$support->email}}"><small>{{ $support->email }}</small></a>
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>

            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>

<script src="{{ asset('js/auth.js') }}"></script>

<script>
    function msieversion() {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0) // If Internet Explorer, return version number
        {
            return true;

        } else // If another browser, return 0
        {
            return false;
        }
    }
    if (msieversion() === true) {
        alert("You are using unsupported browser, please use Google Chrome or any other browser except Internet Explorer.")
    }

</script>
@stop
