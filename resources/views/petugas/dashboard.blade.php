<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Petugas</title>
</head>
<body>
    <h1>Dashboard Petugas</h1>
    <p>Selamat datang, {{ Auth::user()->name }}</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>