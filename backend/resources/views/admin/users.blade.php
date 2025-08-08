<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin • Users</title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,Helvetica,Arial,sans-serif;padding:20px;background:#f7f7f7;color:#222}
        .container{max-width:1000px;margin:0 auto;background:#fff;border:1px solid #e5e5e5;border-radius:8px;padding:20px}
        h1{margin-top:0}
        table{width:100%;border-collapse:collapse;margin-top:10px}
        th,td{padding:8px 10px;border-bottom:1px solid #eee;text-align:left}
        .form-card{margin-top:24px;padding:16px;border:1px solid #eee;border-radius:8px;background:#fafafa}
        label{display:block;margin:8px 0 4px}
        input,select{padding:8px;border:1px solid #ccc;border-radius:6px;width:100%;max-width:360px}
        .row{display:flex;gap:16px;flex-wrap:wrap}
        .btn{padding:10px 14px;border:1px solid #333;background:#333;color:#fff;border-radius:6px;cursor:pointer}
        .alert{padding:10px 14px;border-radius:6px;background:#e6ffed;border:1px solid #b7eb8f;color:#135200;margin-bottom:10px}
    </style>
</head>
<body>
<div class="container">
    <h1>Admin • Users</h1>
    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    <h2>Existing Users</h2>
    <table>
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
                <td>{{ $u->roles->pluck('name')->join(', ') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No users found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="form-card">
        <h3>Create User</h3>
        <form method="post" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="row">
                <div>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}">
                    @error('name')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                    @error('email')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    @error('password')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role')<div style="color:#c00">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="margin-top:16px">
                <button class="btn" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
