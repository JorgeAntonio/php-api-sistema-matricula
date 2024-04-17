<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';
?>
<div class="form-container">
    <h1 class="title">Login de Usuarios</h1>
    <form id="form-login" action="" method="post">
        <label for="username">Nombre de usuario:</label>
        <input type="text" id="username" name="username" required>
        <span id="usernameError" style="color: red;"></span>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <span id="passwordError" style="color: red;"></span>
        <input type="submit" value="Iniciar sesión">
    </form>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';
?>