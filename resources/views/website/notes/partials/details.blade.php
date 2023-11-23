<div class="note-content">
    <h5 class="note-title">
        <span>{{$note->title}}</span>
    </h5>
    <h6 class="note-group">
        <span>{{$note->group ? 'Group: ' . $note->group->title : ''}}</span>
    </h6>
    <div class="note-text">
        <span>{!! nl2br($note->text) !!}</span>
    </div>
</div>
