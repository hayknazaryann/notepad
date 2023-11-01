@component('website.layouts.app')
    @section('css')
        <link rel="stylesheet" href="{{asset('website/css/notepad.css')}}?ver={{ filemtime(public_path('website/css/notepad.css')) }}">
    @endsection
    @section('content')
        <div class="page-content container">
            <div class="title-row">
                <div class="input-row">
                    <input type="text" id="note-title" name="title" form="note-form" placeholder="Write note title" value="{{\App\Models\Note::defaultTitle()}}">
                    <span class="input-icon">
                        <img src="{{asset('website/icons/pen.png')}}" alt="">
                    </span>
                </div>

            </div>
            <div class="page-row">
                <div class="notes-list">
                    <div class="notes-search">
                        <input type="text" placeholder="Search" id="search" >
                    </div>
                    <div class="note-items">
                        @include('website.notes.partials.items', [
                            'data' => $notes
                          ])
                    </div>
                </div>
                <div class="form-content">
                    @include('website.notes.partials.form')
                </div>
                <div class="actions-panel">
                    @include('website.notes.partials.actions')
                </div>
            </div>
        </div>
    @endsection
    @section('js')
        <script src="{{ asset('vendors/docx/index.js') }}"></script>
        <script src="{{ asset('vendors/docx/FileSaver.js') }}"></script>
        <script src="{{ asset('vendors/jspdf/jspdf.umd.js') }}" defer></script>
        <script src="{{asset('website/js/notepad.js')}}?ver={{ filemtime(public_path('website/js/notepad.js')) }}" defer></script>
    @endsection
@endcomponent
