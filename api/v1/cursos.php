<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controllers/CursoController.php';

$method = $_SERVER['REQUEST_METHOD'];

//Procesar las solicitudes según el método
switch($method) {
    case 'GET':
        $curso = new CursoController();
        //traer un solo curso por id
        if(isset($_GET['id'])) {
            $curso->readOne($_GET['id']);
        } else if(isset($_GET['semestre'])) {
            //traer todos los cursos por semestre
            $curso->readBySemestreId($_GET['semestre']);
        } else {
            //traer todos los cursos
            $curso->read();
        }
        break;
    case 'POST':
        $curso = new CursoController();
        //crear curso nuevo
        $curso->create();
        break;
    case 'PUT':
        $curso = new CursoController();
        $curso->update();
        break;
    case 'DELETE':
        $curso = new CursoController();
        $curso->delete();
        break;
    default:
        http_response_code(503);
        echo json_encode(array("error" => "Método no válido"));
        break;
}

