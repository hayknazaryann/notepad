<div class="grid-item" data-key="{{$note->key}}" data-ordering="{{$note->ordering}}" data-id="{{$note->id}}">
    <div class="grid-header">
        <h6 class="note-title">
            <span>{{$note->title}}</span>
        </h6>
        <p class="note-group">
            <span>{{'Group: ' . ($note->group->title ?? '')}}</span>
        </p>
        <div class="note-actions">
            <div class="dropdown download-dropdown">
                <a href="javascript:void(0)" title="Download Note" class=""
                   id="dropdownDownloadButton{{$note->id}}" data-bs-toggle="dropdown" aria-expanded="false"
                >
                                <span class="material-icons">
                                download
                                </span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownDownloadAsButton{{$note->id}}">
                    @foreach(\App\Enums\Extensions::all() as $extension)
                        <li>
                            <a class="dropdown-item download-note" href="{{route('notes.download', ['key' => $note->key, 'extension' => $extension])}}">
                                {{ strtoupper($extension) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="view-action">
                <a href="{{route('notes.view', $note->key)}}" class="view-note" title="Edit Note">
                    <span class="material-icons">
                    visibility
                    </span>
                </a>
            </div>
            <div class="view-action">
                <a href="{{route('notes.edit', $note->key)}}" class="edit-note" title="Edit Note">
                    <span class="material-icons">
                    edit_note
                    </span>
                </a>
            </div>
            <div class="delete-action">
                <a href="{{route('notes.delete', $note->key)}}" class="delete-item" title="Delete Note">
                    <span class="material-icons">
                    delete_forever
                    </span>
                </a>
            </div>
        </div>
        <hr/>
    </div>
    <div class="grid-body {{$note->password ? 'locked' : ''}}">
        <div class="text">
            @if($note->password)
                <span class="material-icons">
                    lock
                </span>
            @else
                <span>
                    {{substr($note->text, 0, 100) . '...'}}
                </span>
            @endif
        </div>
    </div>
    <div class="grid-footer">

    </div>
</div>
