<div class="grid-filters filters">
    <form action="{{route('notes.items')}}" id="filters-form" onSubmit="return false;">
        <input type="hidden" name="page" id="page" value="{{request()->get('page') ?? 1}}">
        <div class="input-row">
            <label for="keyword">{{__('Title')}}</label>
            <input type="text" name="keyword" id="keyword" value="{{request()->get('keyword') ?? ''}}"
                   placeholder="Search by title">
        </div>
        <div class="input-row">
            <label for="group">{{__('Group')}}</label>
            <select class="select2" name="group" id="group" aria-label="{{__('Search by group')}}">
                <option value=""></option>
                @forelse($groups as $group)
                    <option
                        value="{{$group->id}}" {{request()->get('group') == $group->id ? 'selected' : ''}}>
                        {{$group->title}}
                    </option>
                @empty
                @endforelse
            </select>
        </div>
        <div class="input-row">
            <label for="pageSize">{{__('Page Size')}}</label>
            <select class="select2" name="pageSize" id="pageSize" aria-label="{{__('Limit')}}">
                @foreach(\App\Enums\PageSizes::all() as $pageSize)
                    <option value="{{$pageSize}}" {{request()->get('pageSize') == $pageSize ? 'selected' : ''}}>
                        {{$pageSize}}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="main-actions">
        <a href="{{route('notes.create')}}" id="new-note" class="button-secondary">
            {{__('New Note')}}
        </a>
    </div>
</div>
