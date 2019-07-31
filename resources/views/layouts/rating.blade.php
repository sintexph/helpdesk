@if(!empty($rating))

    @for($i=0; $i<$rating; $i++)
    <i style="font-size:35px;" class="fa fa-star text-yellow" aria-hidden="true"></i>
    @endfor

    @for($i=0; $i<(5-$rating); $i++)
    <i style="font-size:35px;" class="fa fa-star text-gray" aria-hidden="true"></i>
    @endfor

@else
    <i style="font-size:35px;" class="fa fa-star text-gray" aria-hidden="true"></i>
    <i style="font-size:35px;" class="fa fa-star text-gray" aria-hidden="true"></i>
    <i style="font-size:35px;" class="fa fa-star text-gray" aria-hidden="true"></i>
    <i style="font-size:35px;" class="fa fa-star text-gray" aria-hidden="true"></i>
    <i style="font-size:35px;" class="fa fa-star text-gray" aria-hidden="true"></i>
@endif