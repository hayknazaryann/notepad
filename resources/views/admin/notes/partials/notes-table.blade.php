<table class="table">
    <thead>
        <tr>
            <th scope="col" width="10%">ID</th>
            <th scope="col" width="20%">User</th>
            <th scope="col" width="50%">Text</th>
            <th scope="col" width="20%">Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($notes as $note)
        <tr>
            <th scope="row">{{$note->id}}</th>
            <td>
                {{$note->user ? $note->user->name : ''}}
            </td>
            <td>
                {{$note->text}}
            </td>
            <td>

            </td>
        </tr>
    @empty
        <tr>
            <td class="text-center" colspan="4">
                Empty data
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
<div class="categories_pagination justify-content-center">
    {!! $notes->links('admin.partials.pagination') !!}
</div>
