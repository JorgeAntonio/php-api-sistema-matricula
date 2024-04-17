<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Model/Semestre.php';

class SemestreController
{
    private $database;
    private $db;
    private $items;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->items = new Semestre($this->db);
    }

    public function read()
    {
        header("Access-Control-Allow-Methods: GET");
        $stmt = $this->items->getSemestres();
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
            echo json_encode(["error" => "No se encontraron semestres"]);
        }
    }

    public function readOne($id)
    {
        header("Access-Control-Allow-Methods: GET");
        $this->items->id = isset($_GET['id']) ? $_GET['id'] : die();
        $this->items->getSemestre($id);
        if ($this->items->id != null) {
            $arrSemestre = array(
                "id" => $this->items->id,
                "nombre" => $this->items->nombre,
                "carrera_id" => $this->items->carrera_id
            );
            http_response_code(200);
            echo json_encode($arrSemestre);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontró el semestre"]);
        }
    }

    public function create()
    {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->nombre) || empty($data->carrera_id)) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos"]);
            return;
        }

        $this->items->nombre = $data->nombre;
        $this->items->carrera_id = $data->carrera_id;

        if ($this->items->createSemestre()) {
            http_response_code(201);
            echo json_encode(["message" => "Semestre creado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Semestre no creado"]);
        }
    }

    public function update()
    {
        header("Access-Control-Allow-Methods: PUT");
        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;
        $this->items->nombre = $data->nombre;
        $this->items->carrera_id = $data->carrera_id;

        if ($this->items->updateSemestre()) {
            http_response_code(200);
            echo json_encode(["message" => "Semestre actualizado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Semestre no actualizado"]);
        }
    }

    public function delete()
    {
        header("Access-Control-Allow-Methods: DELETE");
        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;
        if ($this->items->deleteSemestre()) {
            http_response_code(200);
            echo json_encode(["message" => "Semestre eliminado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Semestre no eliminado"]);
        }
    }

}