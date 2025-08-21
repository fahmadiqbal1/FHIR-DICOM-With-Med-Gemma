<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Test</title>
</head>
<body>
    <h1>Doctor Dashboard - Working!</h1>
    <p>Welcome, Dr. {{ Auth::user()->name }}</p>
    <p>This is a simplified test version of the doctor dashboard.</p>
    
    <nav>
        <a href="/patients">Patients</a> |
        <a href="/medgemma">AI Analysis</a> |
        <a href="/reports">Reports</a>
    </nav>
</body>
</html>
