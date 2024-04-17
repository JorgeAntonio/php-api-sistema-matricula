<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/PerfilController.php';

$method = $_SERVER['REQUEST_METHOD'];

//Procesar las solicitudes según el método
switch($method) {
    case 'GET':
        $perfil = new PerfilController();
        //traer un solo perfil por id
        if(isset($_GET['id'])) {
            $perfil->readOne($_GET['id']);
        }
        //traer perfil por id de usuario
        else if(isset($_GET['id_usuario'])) {
            $perfil->readOneByIdUsuario($_GET['id_usuario']);
        } else {
            //traer todos los perfiles
            $perfil->read();
        }
        break;
    case 'POST':
        $perfil = new PerfilController();
        //crear perfil nuevo
        $perfil->create();
        break;
    case 'PUT':
        $perfil = new PerfilController();
        $perfil->update();
        break;
    case 'DELETE':
        $perfil = new PerfilController();
        $perfil->delete();
        break;
    default:
        http_response_code(503);
        echo json_encode(array("error" => "Método no válido"));
        break;
}