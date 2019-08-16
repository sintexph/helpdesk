<div class="attachment-block clearfix">
    @if(!empty($image))
    <img class="attachment-img" src="{{ $image }}" alt="Attachment Image">
    @endif
    <div class="attachment-pushed">
        <h4 class="attachment-heading"><a href="{{ $url }}">{{$title}}</a></h4>
        <div class="attachment-text">
            {{ $description }}
        </div>
    </div>
</div>
