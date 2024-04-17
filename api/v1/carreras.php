<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/CarreraController.php';

$method = $_SERVER['REQUEST_METHOD'];

//Procesar las solicitudes según el método
switch($method) {
    case 'GET':
        $carrera = new CarreraController();
        //traer una sola carrera por id
        if(isset($_GET['id'])) {
            $carrera->readOne($_GET['id']);
        } else {
            //traer todas las carreras
            $carrera->read();
        }
        break;
    case 'POST':
        $carrera = new CarreraController();
        //crear carrera nueva
        $carrera->create();
        break;
    case 'PUT':
        $carrera = new CarreraController();
        $carrera->update();
        break;
    case 'DELETE':
        $carrera = new CarreraController();
        $carrera->delete();
        break;
    default:
        http_response_code(503);
        echo json_encode(array("error" => "Método no válido"));
        break;
}
