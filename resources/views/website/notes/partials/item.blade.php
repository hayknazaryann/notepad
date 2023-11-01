<div class="note-item">
    <div class="note-title">
        <span class="material-icons">article</span>
        <a href="{{route('notepad.view', $note->key)}}" class="view-note">
            {{$note->title}}
        </a>
    </div>
    <div class="note-actions">
        <div class="dropdown download-dropdown">
            <a href="javascript:void(0)" title="Download" class=""
               id="dropdownDownloadButton{{$note->id}}" data-bs-toggle="dropdown" aria-expanded="false"
            >
                                <span class="material-icons">
                                download
                                </span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownDownloadAsButton{{$note->id}}">
                @foreach(\App\Enums\Extensions::all() as $extension)
                    <li>
                        <a class="dropdown-item download-note" href="{{route('notepad.download', ['key' => $note->key, 'extension' => $extension])}}">
                            {{ strtoupper($extension) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="delete-action">
            <a href="{{route('notepad.delete', $note->key)}}" class="delete-item">
            <span class="material-icons">
            delete_forever
            </span>
            </a>
        </div>

    </div>
</div>
