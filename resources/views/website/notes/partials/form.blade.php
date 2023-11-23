<div class="form-content">
    <form action="{{ $url }}" id="note-form">
        @if($type === 'edit')
            {{method_field('PUT')}}
        @endif

        <div class="inputs-content">
            <div class="input-row">
                <label for="note-title">{{__('Title')}}</label>
                <input type="text" id="note-title" name="title" form="note-form" placeholder="Write note title"
                       value="{{$note->title ?? \App\Models\Note::defaultTitle()}}">
            </div>
            <hr/>
            <div class="input-row">
                <label for="group-tag">{{__('Group')}}</label>
                <select name="group" id="group-tag" form="note-form">
                    <option value=""></option>
                    @if(count($groups))
                        @foreach($groups as $group)
                            <option value="{{$group->title}}" {{isset($note) && $note->group_id == $group->id ? 'selected' : ''}}>{{$group->title}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <hr/>
            <div class="input-row">
                <label for="password">{{__('Password')}}</label>
                <input type="text" id="password" name="password" form="note-form" placeholder="Write password" value="">
            </div>
        </div>
        <div class="textarea-content">
            <textarea
                name="text" id="note"
                placeholder="{{__('Write your note here')}}"
            >{{$note->text ?? ''}}</textarea>
        </div>
    </form>
    <div class="actions-panel">
        @include('website.notes.partials.actions')
    </div>
</div>
