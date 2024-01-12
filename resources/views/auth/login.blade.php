<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form method="post" action="{{ url('/login') }}">
        @csrf
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>

        <label for="pass">Contraseña:</label>
        <input type="password" name="pass" required>

        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
