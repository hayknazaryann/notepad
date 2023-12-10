<div class="access-form-content">
    <form action="{{route('notes.access', $note->key)}}" id="access-form">
        <div class="inputs-content">
            <div class="input-row">
                <label for="user">{{__('User')}}</label>
                <select class="select2" name="user_id" id="user" aria-label="{{__('Choose User')}}">
                    <option value=""></option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">
                            {{$user->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <hr/>
            <div class="input-row">
                <label for="permission">{{__('Permission')}}</label>
                <select class="select2" name="access" id="access" aria-label="{{__('Choose Permission')}}">
                    @foreach(\App\Enums\Permissions::all() as $pValue => $pLabel)
                        <option value="{{$pValue}}">
                            {{$pLabel}}
                        </option>
                    @endforeach
                </select>
            </div>
            <hr/>
            <div class="form-button-row">
                <button type="button" id="add-user" class="btn button-secondary">
                    {{__('Add')}}
                </button>
            </div>
        </div>
    </form>

    <div class="access-list">
        @include('website.notes.partials.access-items')
    </div>

</div>
