@foreach($data as $note)
    @include('website.notes.partials.item')
@endforeach
@if($showBtn)
    @include('website.partials.load-more', [
      'url' => route('load_more'),
      'content' => 'note-items',
      'folder' => 'notes',
      'item' => 'note-item',
      'model' => 'Note',
      'id' => null,
      'method' => 'list',
      'limit' => 12
    ])
@endif
