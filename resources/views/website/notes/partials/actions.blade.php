<div class="actions-list">
    <a href="javascript:void(0)" id="import" title="Import file">
                            <span class="material-icons">
                            file_upload
                            </span>
        {{__('Import')}}
        <form class="hidden" method="post" action="{{route('notes.import')}}" enctype="multipart/form-data">
            <input type="file" id="import-file" name="file">
        </form>
    </a>
    <a href="javascript:void(0)" id="save" title="Save Note">
                            <span class="material-icons">
                            save
                            </span>
        {{__('Save')}}
    </a>
    <div class="dropdown">
        <a href="javascript:void(0)" id="save-as" title="Save and Download" class="dropdown-toggle"
           id="dropdownSaveAsButton" data-bs-toggle="dropdown" aria-expanded="false"
        >
                                <span class="material-icons">
                                save_as
                                </span>
            {{__('Save as')}}
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownSaveAsButton">
            @foreach(\App\Enums\Extensions::all() as $extension)
                <li>
                    <a class="dropdown-item save-and-download" href="javascript:void(0)" data-extension="{{ $extension }}">
                        {{ strtoupper($extension) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
