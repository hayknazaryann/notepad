@component('website.layouts.app')
    @section('css')
        <link rel="stylesheet" href="{{asset('website/css/grid.css')}}?ver={{ filemtime(public_path('website/css/grid.css')) }}">
    @endsection
    @section('content')
        <div class="page-content container">
            <div class="grid-filters filters">
                <form action="{{route('notes.items')}}" id="filters-form" onSubmit="return false;">
                    <div class="input-row">
                        <label for="keyword">{{__('Title')}}</label>
                        <input type="text" name="keyword" id="keyword" value="{{request()->get('keyword') ?? ''}}"
                               placeholder="Search by title">
                    </div>
                    <div class="input-row">
                        <label for="group">{{__('Group')}}</label>
                        <select name="group" id="group">
                            <option value=""></option>
                            @forelse($groups as $group)
                                <option
                                    value="{{$group->id}}" {{request()->get('group') == $group->id ? 'selected' : ''}}>
                                    {{$group->title}}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="input-row">
                        <label for="pageSize">{{__('Page Size')}}</label>
                        <select name="pageSize" id="pageSize">
                            @foreach(\App\Enums\PageSizes::all() as $pageSize)
                                <option value="{{$pageSize}}" {{request()->get('pageSize') == $pageSize ? 'selected' : ''}}>
                                    {{$pageSize}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div class="main-actions">
                    <a href="{{route('notes.create')}}" id="new-note" class="button-secondary">
                        {{__('New Note')}}
                    </a>
                </div>
            </div>
            <div class="grid-row" id="grid-items" data-url="{{route('notes.ordering')}}">
                @if(count($notes))
                    @include('website.notes.partials.grid-items', [
                        'data' => $notes
                    ])
                @else
                    <div class="empty-content">
                        {{__('Empty')}}
                    </div>
                @endif

            </div>

        </div>
    @endsection
    @section('js')
        <script src="{{ asset('vendors/docx/index.js') }}"></script>
        <script src="{{ asset('vendors/docx/FileSaver.js') }}"></script>
        <script src="{{ asset('vendors/jspdf/jspdf.umd.js') }}" defer></script>
        <script src="{{ asset('vendors/sortable/sortable.js') }}" defer></script>
        <script src="{{asset('website/js/notepad.js')}}?ver={{ filemtime(public_path('website/js/notepad.js')) }}"
                defer></script>
        <script src="{{asset('website/js/filters.js')}}?ver={{ filemtime(public_path('website/js/filters.js')) }}"
                defer></script>
    @endsection
@endcomponent
