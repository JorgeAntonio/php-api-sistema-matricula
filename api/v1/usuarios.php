<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/UsuarioController.php';

$method = $_SERVER['REQUEST_METHOD'];

//Procesar las solicitudes según el método
switch($method) {
    case 'GET':
        $usuario = new UsuarioController();
        //traer un solo usuario por id
        if(isset($_GET['id'])) {
            $usuario->readOne($_GET['id']);
        } else {
            //traer todos los usuarios
            $usuario->read();
        }
        break;
    case 'POST':
        $usuario = new UsuarioController();
        if(isset($_GET['login'])) {
            //login de usuario
            $usuario->login();
        } else {
            //crear usuario nuevo
            $usuario->create();
        }
        break;
    case 'PUT':
        $usuario = new UsuarioController();
        $usuario->update();
//        Actualiza el Api Token
//        if(isset($_GET['update']) && $_GET['update'] === 'token') {
//            $usuario->updateApiToken();
//        } else {
            //Actualizar datos del usuario
//            $usuario->update();
//        }
        break;
    case 'DELETE':
        $usuario = new UsuarioController();
        $usuario->delete();
        break;
    default:
        http_response_code(503);
        echo json_encode(array("error" => "Método no válido"));
        break;
}