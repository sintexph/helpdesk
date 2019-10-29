<div class="attachment-block clearfix">
    @if(!empty($image))

    <a class="venobox" href="{{ $image }}">
        <img class="attachment-img" src="{{ $image }}" alt="Attachment Image" />
    </a>
    @endif
    <div class="attachment-pushed">
        <h4 class="attachment-heading"><a href="{{ $url }}">{{$title}}</a></h4>
        <div class="attachment-text">
            {{ $description }}
        </div>
    </div>
</div>
