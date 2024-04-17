<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Model/Perfil.php';

class PerfilController
{
    private $database;
    private $db;
    private $items;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->items = new Perfil($this->db);
    }

    public function read()
    {
        header("Access-Control-Allow-Methods: GET");
        $stmt = $this->items->getPerfiles();
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
            echo json_encode(["error" => "No se encontraron perfiles"]);
        }
    }

    public function readOne($id)
    {
        header("Access-Control-Allow-Methods: GET");
        $this->items->id = isset($_GET['id']) ? $_GET['id'] : die();
        $this->items->getPerfil($id);
        if ($this->items->id != null) {
            $arrPerfil = array(
                "id" => $this->items->id,
                "id_usuario" => $this->items->id_usuario,
                "carrera" => $this->items->carrera,
                "semestre" => $this->items->semestre,
                "created_at" => $this->items->created_at,
                "updated_at" => $this->items->updated_at
            );
            http_response_code(200);
            echo json_encode($arrPerfil);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Perfil no encontrado"]);
        }
    }

    public function readOneByIdUsuario($id_usuario)
    {
        header("Access-Control-Allow-Methods: GET");
        $perfil = $this->items->getPerfilByIdUsuario($id_usuario);
        if ($perfil != null) {
            http_response_code(200);
            echo json_encode($perfil);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Perfil no encontrado"]);
        }
    }

    public function create()
    {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->id_usuario) || empty($data->carrera) || empty($data->semestre)) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos"]);
            return;
        }

        // Verificar si el usuario ya tiene un perfil
        $perfil = $this->items->getPerfilByIdUsuario($data->id_usuario);

        if ($perfil != null) {
            http_response_code(400);
            echo json_encode(["error" => "El usuario ya tiene un perfil"]);
            return;
        }

        $this->items->id_usuario = $data->id_usuario;
        $this->items->carrera = $data->carrera;
        $this->items->semestre = $data->semestre;

        if ($this->items->createPerfil()) {
            // inicializar la sesion del perfil
            session_start();
            $_SESSION['perfil_token'] = $this->items->perfil_token;
            http_response_code(201);
            echo json_encode(["success" => "Perfil creado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Error al crear perfil"]);
        }
    }

    public function update()
    {
        header("Access-Control-Allow-Methods: PUT");
        $data = json_decode(file_get_contents("php://input"));

        $this->items->id = $data->id;
        $this->items->id_usuario = $data->id_usuario;
        $this->items->carrera = $data->carrera;
        $this->items->semestre = $data->semestre;

        if ($this->items->updatePerfil()) {
            http_response_code(200);
            echo json_encode(["success" => "Perfil actualizado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Error al actualizar perfil"]);
        }
    }

    public function delete()
    {
        header("Access-Control-Allow-Methods: DELETE");
        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;
        if ($this->items->deletePerfil()) {
            http_response_code(200);
            echo json_encode(["success" => "Perfil eliminado"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Error al eliminar perfil"]);
        }
    }

}