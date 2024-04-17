<?php
session_start();
//verficar si hay una sesion activa
if(!isset($_SESSION['user']) && !isset($_SESSION['token'])) {
    header('Location: /usuarios/login'); //ruta de login
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';

echo '<h1 class="title">Bienvenido, '. $_SESSION['user'] .'</h1>';
echo '<p>Token: '. $_SESSION['token'] .'</p>';
echo '<a class="button btnGreen" href="/users/logout">Cerrar sesión</a>';

include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';