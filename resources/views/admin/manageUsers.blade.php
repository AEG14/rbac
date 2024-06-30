@extends('mainLayout')

@section('title', 'Manage Users')

<!-- Alert messages -->
<div class="row mt-3">
    <div class="col">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <a href="{{ route('dash') }}" class="link-dark">Back</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <h3>Users</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>
        <form action="{{ route('updateUserRole', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <select name="role" class="form-select">
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mt-2">Update</button>
        </form>
    </td>
</tr>
@endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col">
            <h3>Create New Role</h3>
            <form action="{{ route('createRole') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="roleName" class="form-label">Role Name</label>
                    <input type="text" name="role_name" id="roleName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="permissions" class="form-label">Permissions</label>
                    <div id="permissions">
                        @foreach($permissions as $permission)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}">
                            <label class="form-check-label" for="permission{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Create Role</button>
            </form>
        </div>
    </div>
</div>
@endsection
