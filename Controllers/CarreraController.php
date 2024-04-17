<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Model/Carrera.php';

class CarreraController
{
    private $database;
    private $db;
    private $items;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->items = new Carrera($this->db);
    }

    public function read()
    {
        header("Access-Control-Allow-Methods: GET");
        $stmt = $this->items->getCarreras();
        $itemCount = $stmt->rowCount();

        if ($itemCount > 0) {
            $arr = array();
            $arr["body"] = array();
            $arr["itemCount"] = $itemCount;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($arr["body"], $row);
            }
            echo json_encode($arr);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontraron carreras"]);
        }
    }

    public function readOne($id)
    {
        header("Access-Control-Allow-Methods: GET");
        $this->items->id = isset($_GET['id']) ? $_GET['id'] : die();
        $this->items->getCarrera($id);
        if ($this->items->id != null) {
            $arrCarrera = array(
                "id" => $this->items->id,
                "nombre" => $this->items->nombre,
                "descripcion" => $this->items->descripcion,
                "created_at" => $this->items->created_at,
                "updated_at" => $this->items->updated_at
            );
            http_response_code(200);
            echo json_encode($arrCarrera);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontró la carrera"]);
        }
    }

    public function create()
    {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input"));

        if(empty($data->nombre) || empty($data->descripcion)){
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos"]);
            return;
        }

        $this->items->nombre = $data->nombre;
        $this->items->descripcion = $data->descripcion;

        if($this->items->createCarrera()){
            http_response_code(201);
            echo json_encode(["message" => "Carrera creada"]);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo crear la carrera"));
        }
    }

    public function update()
    {
        header("Access-Control-Allow-Methods: PUT");
        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;

        $this->items->nombre = $data->nombre;
        $this->items->descripcion = $data->descripcion;

        if ($this->items->updateCarrera()){
            http_response_code(200);
            echo json_encode(["message" => "Datos de carrera actualizados"]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo actualizar la carrera"]);
        }
    }

    public function delete()
    {
        header("Access-Control-Allow-Methods: DELETE");
        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;

        if($this->items->deleteCarrera()){
            http_response_code(200);
            echo json_encode(["message" => "Carrera eliminada"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "No se pudo eliminar la carrera"]);
        }
    }
}