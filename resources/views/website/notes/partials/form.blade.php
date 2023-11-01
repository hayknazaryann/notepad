<form action="{{route('notepad.store')}}" data-type="create" id="note-form">
    <div class="textarea-content">
            <textarea
                name="text" id="note"
                placeholder="{{__('Write your note here')}}"
            ></textarea>
    </div>
</form>
