<?php
//Autocarga de clases
spl_autoload_register(function ($class_name) {
    include 'Controllers/' . $class_name . '.php';
});
//Captura la URL Solicitada
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//Basado en la URL, se determina que controlador y metodo se ejecutará
switch($url) {
    case '/':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/home.php';
        break;
    case '/users':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/users/list.php';
        break;
    case '/users/create':
    case '/users/update':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/users/actions.php';
        break;
    case '/users/login':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/users/login.php';
        break;
    case '/users/logout':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/users/logout.php';
        break;
    case '/usuarios/register':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/usuarios/register.php';
        break;
    case '/usuarios':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/usuarios/list.php';
        break;
    case '/usuarios/create':
    case '/usuarios/update':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/usuarios/actions.php';
        break;
    case '/usuarios/login':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/usuarios/login.php';
        break;
    case '/usuarios/logout':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/usuarios/logout.php';
        break;
    case '/perfiles/perfil':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/perfiles/perfil.php';
        break;
    case '/carreras':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/carreras/list.php';
        break;
    case '/carreras/create':
    case '/carreras/update':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/carreras/actions.php';
        break;
    case '/semestres':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/semestres/list.php';
        break;
    case '/semestres/create':
    case '/semestres/update':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/semestres/actions.php';
        break;
    case '/cursos':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/cursos/list.php';
        break;
    case '/cursos/create':
    case '/cursos/update':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/cursos/actions.php';
        break;
    case '/cursos/materias':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/cursos/form.php';
        break;
    case '/matriculas':
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/matriculas/list.php';
        break;

    default:
        //cargar la vista de error 404
        require $_SERVER['DOCUMENT_ROOT'] . '/resources/views/404.php';
        break;
}