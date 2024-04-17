<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/login-header.php';
?>

<div class="form-container">
    <img src="/resources/assets/book-white.png" class="logo" alt="Logo">
    <h1 class="title">SISTEMA DE MATRICULA</h1>
    <form id="form-register-usuarios" action="" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <span id="nombreError" style="color: red;"></span>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
        <span id="apellidoError" style="color: red;"></span>
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>
        <span id="emailError" style="color: red;"></span>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <span id="passwordError" style="color: red;"></span>
        <input type="submit" value="Registrarse">
        <a href="/usuarios/login">Iniciar sesión</a>
    </form>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/client-footer.php';
?>

