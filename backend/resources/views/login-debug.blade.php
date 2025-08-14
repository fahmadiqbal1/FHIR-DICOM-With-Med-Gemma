<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Debug - FHIR DICOM MedGemma</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { padding: 8px; width: 100%; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        .debug-info { background: #f0f0f0; padding: 15px; margin: 20px 0; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login Debug Page</h1>
        
        <div class="debug-info">
            <h3>Current Session Info:</h3>
            <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
            <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
            <p><strong>Session Driver:</strong> {{ config('session.driver') }}</p>
            <p><strong>Session Lifetime:</strong> {{ config('session.lifetime') }} minutes</p>
        </div>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="fahmad_iqbal@hotmail.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" value="123456" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="debug-info">
            <h3>Form Debug Info:</h3>
            <p>This form should submit to: {{ route('login.submit') }}</p>
            <p>CSRF Token in form: <span id="csrf-token">{{ csrf_token() }}</span></p>
        </div>
        
        <script>
            // Show form data when submitting
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form submitting...');
                console.log('CSRF Token:', document.querySelector('input[name="_token"]').value);
                console.log('Email:', document.querySelector('input[name="email"]').value);
                console.log('Password:', document.querySelector('input[name="password"]').value);
            });
        </script>
    </div>
</body>
</html>
