<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/login-header.php';
?>
    <div class="form-container">
        <img src="/resources/assets/book-white.png" class="logo" alt="Logo">
        <h1 class="title">SISTEMA DE MATRICULA</h1>
        <form id="form-login-usuarios" action="" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span id="emailError" class="error" style="color: red;"></span>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password"  required>
            <span id="passwordError" class="error" style="color: red;"></span>
            <input type="submit" value="Iniciar sesión">
            <a href="/usuarios/register">Registrarse</a>
        </form>
    </div>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/client-footer.php';
?>