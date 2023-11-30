@foreach($data as $note)
    @include('website.notes.partials.grid-item')
@endforeach
<div class="load-more-content">
    @if($currentPage > 1)
        <a href="javascript:void(0)" class="button-secondary" id="prev">
            {{__('Prev')}}
        </a>
    @endif
    @if($loadMore)
        <a href="javascript:void(0)" class="button-secondary" id="next">
            {{__('Next')}}
        </a>
    @endif
</div>



