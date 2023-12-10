@component('website.layouts.app')
    @section('css')
        <link rel="stylesheet" href="{{asset('website/css/grid.css')}}?ver={{ filemtime(public_path('website/css/grid.css')) }}">
    @endsection
    @section('content')
        <div class="page-content container">
            @include('website.notes.partials.filters')
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
        <script src="{{asset('website/js/notepad.js')}}?ver={{ filemtime(public_path('website/js/notepad.js')) }}" defer></script>
        <script src="{{asset('website/js/filters.js')}}?ver={{ filemtime(public_path('website/js/filters.js')) }}" defer></script>
    @endsection
@endcomponent
