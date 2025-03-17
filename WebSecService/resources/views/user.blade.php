<!-- @extends('layouts.master')

@section('title', 'Users List')

@section('content')
    <div class="card">
        <div class="card-header">Users List</div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search users" value="{{ request()->search }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
            <a href="{{ route('users.create') }}" class="btn btn-success mb-3">Add User</a>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($users) && $users->count() > 0)
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No users found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            @if(isset($users))
                {{ $users->links() }}
            @endif
        </div>
    </div>
@endsection -->
