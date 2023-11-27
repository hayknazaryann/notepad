<div class="password-form-content justify-content-center">
    <form action="{{ route('notes.unlock', $note->key) }}" id="note-password-form">
        <input type="hidden" name="action" value="{{$action}}">
        <div class="input-row">
            <input type="password" name="password" id="password" placeholder="{{__('Write password')}}" >
        </div>
        <div class="input-row">
            <button type="submit" id="unlock-note" class="btn button-secondary">
                {{__('Open')}}
            </button>
        </div>
    </form>
</div>
