<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Model/Curso.php';

class CursoController
{
    private $database;
    private $db;
    private $items;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->items = new Curso($this->db);
    }

    public function read()
    {
        header("Access-Control-Allow-Methods: GET");
        $stmt = $this->items->getCursos();
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
            echo json_encode(["error" => "No se encontraron cursos"]);
        }
    }

    public function readOne($id)
    {
        header("Access-Control-Allow-Methods: GET");
        $this->items->id = isset($_GET['id']) ? $_GET['id'] : die();
        $this->items->getCurso($id);
        if ($this->items->id != null) {
            $arrCurso = array(
                "id" => $this->items->id,
                "nombre" => $this->items->nombre,
                "descripcion" => $this->items->descripcion,
                "cupos" => $this->items->cupos,
                "id_semestre" => $this->items->id_semestre,
                "created_at" => $this->items->created_at,
                "updated_at" => $this->items->updated_at
            );
            http_response_code(200);
            echo json_encode($arrCurso);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontró el curso"]);
        }
    }

    public function readBySemestreId($id)
    {
        header("Access-Control-Allow-Methods: GET");

        // Obtener cursos por el ID del semestre proporcionado
        $stmt = $this->items->getCursosBySemestreId($id);

        // Contar el número de resultados
        $itemCount = $stmt->rowCount();

        // Verificar si se encontraron cursos
        if ($itemCount > 0) {
            $arr = array();
            $arr["body"] = array();
            $arr["itemCount"] = $itemCount;

            // Obtener los resultados y agregarlos al array
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($arr["body"], $row);
            }
            echo json_encode($arr); // Devolver los cursos encontrados
        } else {
            // No se encontraron cursos para el semestre dado
            http_response_code(404);
            echo json_encode(["error" => "No se encontraron cursos para el semestre dado"]);
        }
    }


    public function create()
    {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->nombre) || empty($data->descripcion) || empty($data->cupos) || empty($data->id_semestre)) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos"]);
            return;
        }

        $this->items->nombre = $data->nombre;
        $this->items->descripcion = $data->descripcion;
        $this->items->cupos = $data->cupos;
        $this->items->id_semestre = $data->id_semestre;
        if ($this->items->createCurso()) {
            http_response_code(201);
            echo json_encode(["message" => "Curso creado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "No se pudo crear el curso"]);
        }
    }

    public function update()
    {
        header("Access-Control-Allow-Methods: PUT");
        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;
        $this->items->nombre = $data->nombre;
        $this->items->descripcion = $data->descripcion;
        $this->items->cupos = $data->cupos;
        $this->items->id_semestre = $data->id_semestre;
        if ($this->items->updateCurso()) {
            http_response_code(200);
            echo json_encode(["message" => "Curso actualizado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "No se pudo actualizar el curso"]);
        }
    }

    public function delete()
    {
        header("Access-Control-Allow-Methods: DELETE");
        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;
        if ($this->items->deleteCurso()) {
            http_response_code(200);
            echo json_encode(["message" => "Curso eliminado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "No se pudo eliminar el curso"]);
        }
    }

}