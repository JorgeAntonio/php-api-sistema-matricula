<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/UserController.php';

$method = $_SERVER['REQUEST_METHOD'];

//Procesar las solicitudes según el método
switch($method) {
    case 'GET':
        $user = new UserController();
        //traer un solo usuario por id
        if(isset($_GET['id'])) {
            $user->readOne($_GET['id']);
        } else {
            //traer todos los usuarios
            $user->read();
        }
        break;
    case 'POST':
        $user = new UserController();
        if(isset($_GET['login'])) {
            //login de usuario
            $user->login();
        } else {
            //crear usuario nuevo
            $user->create();
        }
        break;
    case 'PUT':
        $user = new UserController();
        //Actualiza el Api Token
        if(isset($_GET['update']) && $_GET['update'] === 'token') {
            $user->updateApiToken();
        } else {
            //Actualizar datos del usuario
            $user->update();
        }
        break;
    case 'DELETE':
        $user = new UserController();
        //Eliminar usuario
        $user->delete();
        break;
    default:
        http_response_code(503);
        echo json_encode(array("error" => "Método no válido"));
        break;
}