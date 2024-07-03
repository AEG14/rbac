@extends('mainLayout')

@section('title', 'Manage Users')

@section('page-content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col">
            <a href="{{ route('dash') }}" class="btn btn-link">Back</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button class="btn btn-primary btn-sm" id="showUsersBtn">Show Users</button>
            <button class="btn btn-secondary btn-sm" id="showRolesBtn">Add Roles</button>
        </div>
    </div>

    <!-- Users -->
    <div class="row mt-3" id="usersTable">
        <div class="col">
            <h3>Users</h3>
            <table class="table table-striped table-hover table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Permissions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td style="font-size: 1.2rem;">{{ $user->name }}</td>
                            <td style="font-size: 1.2rem;">{{ $user->email }}</td>


                            <td>
                                <form action="{{ route('updateUserRole', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="form-select form-select-sm">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Update</button>
                                </form>
                            </td>
                            <td>
                                {{-- <ul class="list-unstyled small" style="font-size: 1.2rem;">
                                    @foreach($user->roles->flatMap->permissions->unique('id') as $permission)
                                        <li style="font-size: 1.2rem;">{{ $permission->name }}</li>
                                    @endforeach
                                </ul> --}}

                                <form action="{{ route('updateUserPermissions', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <ul class="list-unstyled small">
                                        @foreach($permissions as $permission)
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}_{{ $user->id }}" {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="permission{{ $permission->id }}_{{ $user->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Update Permissions</button>
                                </form>
                            </td>
                            
                            <td>
                                <form action="{{ route('deleteUser', $user->id) }}" method="POST" id="deleteUserForm{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm delete-user-btn" data-user-id="{{ $user->id }}">Delete</button>
                                </form>
                            </td>
                            
                            
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Add Role -->
    <div class="row m-5 d-none" id="createRoleForm">
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
                                <label class="form-check-label small" for="permission{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Create Role</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('showUsersBtn').addEventListener('click', function() {
        document.getElementById('usersTable').classList.remove('d-none');
        document.getElementById('createRoleForm').classList.add('d-none');
    });

    document.getElementById('showRolesBtn').addEventListener('click', function() {
        document.getElementById('createRoleForm').classList.remove('d-none');
        document.getElementById('usersTable').classList.add('d-none');
    });
    // show Users initially
    document.getElementById('showUsersBtn').click();

    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            if (confirm(`Are you sure you want to delete user with ID ${userId}?`)) {
                document.getElementById(`deleteUserForm${userId}`).submit();
            }
        });
    });
</script>
@endsection
