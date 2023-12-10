<div class="access-item">
    <div class="item-title">
        <span class="material-icons">
        account_circle
        </span>
        {{$noteUser->name}}
    </div>
    <div class="item-body">
        <div class="form-check-row">
            <input class="form-input can-edit" id="can-edit-{{$noteUser->id}}" type="checkbox" {{ $noteUser->hasAccessEdit() ? 'checked' : '' }}>
            <label class="form-label" for="can-edit-{{$noteUser->id}}">
                {{ __('Can Edit') }}
            </label>
        </div>
    </div>
</div>
