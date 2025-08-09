<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin • Users</title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<header class="app-header"><div class="inner"><div class="logo"><div class="mark"></div><span>Admin Panel</span></div><nav class="nav"><a class="btn ghost" href="/app">Dashboard</a><a class="btn ghost" href="/">Welcome</a></nav></div></header>
<div class="container">
    <h1>Admin • Users</h1>
    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    <div class="card">
        <h2>Existing Users</h2>
        <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>@if(!empty($rolesAvailable) && $rolesAvailable) {{ $u->roles->pluck('name')->join(', ') }} @else - @endif</td>
            </tr>
        @empty
            <tr><td colspan="4">No users found.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>

    <div class="card" style="margin-top:16px">
        <h3>Create User</h3>
        <form method="post" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="row">
                <div>
                    <label for="name">Name</label>
                    <input class="input" type="text" id="name" name="name" value="{{ old('name') }}">
                    @error('name')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="email">Email</label>
                    <input class="input" type="email" id="email" name="email" value="{{ old('email') }}">
                    @error('email')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="password">Password</label>
                    <input class="input" type="password" id="password" name="password">
                    @error('password')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
                @if(!empty($rolesAvailable) && $rolesAvailable)
                <div>
                    <label for="role">Role</label>
                    <select class="input" name="role" id="role">
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
                @else
                <div>
                    <label>Role</label>
                    <div class="muted">Roles module not installed; user will be created without roles.</div>
                </div>
                @endif
            </div>
            <div style="margin-top:16px">
                <button class="btn primary" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
