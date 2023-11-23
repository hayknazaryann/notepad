@foreach($data as $note)
    @include('website.notes.partials.grid-item')
@endforeach
@if($pages > 1)
    <div class="load-more-content">
        <div class="pagination-input-content">
            <a href="javascript:void(0)" class="pagination-arrow" id="paginate-prev" data-page="prev">
                <span class="material-icons">
                    arrow_back_ios_new
                </span>
            </a>
            <input type="text" name="page" form="filters-form" id="page" value="{{request()->get('page') ?? 1}}">
            <a href="javascript:void(0)" class="pagination-arrow" id="paginate-next" data-page="next">
                <span class="material-icons">
                    arrow_forward_ios
                </span>
            </a>
        </div>
    </div>
@endif


