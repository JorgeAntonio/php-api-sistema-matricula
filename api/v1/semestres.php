<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/SemestreController.php';

$method = $_SERVER['REQUEST_METHOD'];

//Procesar las solicitudes según el método
switch($method) {
    case 'GET':
        $semestre = new SemestreController();
        //traer un solo semestre por id
        if(isset($_GET['id'])) {
            $semestre->readOne($_GET['id']);
        } else {
            //traer todos los semestres
            $semestre->read();
        }
        break;
    case 'POST':
        $semestre = new SemestreController();
        //crear semestre nuevo
        $semestre->create();
        break;
    case 'PUT':
        $semestre = new SemestreController();
        $semestre->update();
        break;
    case 'DELETE':
        $semestre = new SemestreController();
        $semestre->delete();
        break;
    default:
        http_response_code(503);
        echo json_encode(array("error" => "Método no válido"));
        break;
}
