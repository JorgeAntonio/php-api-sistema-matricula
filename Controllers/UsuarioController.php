<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Model/Usuario.php';

class UsuarioController
{
    private $database;
    private $db;
    private $items;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->items = new Usuario($this->db);
    }
    //Operaciones CRUD
    //Login User
    public function login()
    {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(["error" => "Debe proporcionar email y contraseña"]);
            return;
        }
        //Consultar si existe el username
        $usuario = $this->items->getUsuarioByEmail($data->email);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Email no existe!"]);
            return;
        }
        //Verificar la contraseña
        if (!password_verify($data->password, $usuario['password'])) {
            http_response_code(401);
            echo json_encode(["error" => "Contraseña incorrecta!"]);
            return;
        }
        //Iniciar las sesion del usuario
        session_start();
        $_SESSION['student_id'] = $usuario['id'];
        $_SESSION['student'] = $usuario['email'];
        $_SESSION['student_token'] = $usuario['api_token'];

        http_response_code(200);
        echo json_encode([
            'success' => 'true',
            'student_token' => $usuario['api_token']
        ]);
    }

    //Traer todos los usuarios
    public function read()
    {
        header("Access-Control-Allow-Methods: GET");

        $stmt = $this->items->getUsuarios();
        $itemCount = $stmt->rowCount();

        if ($itemCount > 0) {
            $arr = array();
            $arr["data"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($arr["data"], $row);
            }
            echo json_encode($arr);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontraron usuarios!"]);
        }
    }

    //Traer un solo usuario
    public function readOne($id)
    {
        header("Access-Control-Allow-Methods: GET");

        $this->items->id = isset($_GET['id']) ? $_GET['id'] : die();
        $this->items->getUsuario($id);

        if ($this->items->nombre != null) {
            $arr = array(
                "id" => $this->items->id,
                "nombre" => $this->items->nombre,
                "apellido" => $this->items->apellido,
                "email" => $this->items->email,
                "password" => $this->items->password
            );
            http_response_code(200);
            echo json_encode($arr);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado!"]);
        }

    }

    //Crear un usuario
    public function create()
    {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->nombre) || !isset($data->apellido) || !isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(["error" => "Debe proporcionar nombre, apellido, email y contraseña"]);
            return;
        }
        //Crear el usuario
        $this->items->nombre = $data->nombre;
        $this->items->apellido = $data->apellido;
        $this->items->email = $data->email;
        $this->items->password = password_hash($data->password, PASSWORD_DEFAULT);

        if ($this->items->createUsuario()) {
            http_response_code(201);
            echo json_encode(["success" => "Usuario creado!"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Error al crear usuario!"]);
        }
    }

    //Actualizar un usuario
    public function update()
    {
        header("Access-Control-Allow-Methods: PUT");
        $data = json_decode(file_get_contents("php://input"));

        //Actualizar el usuario
        $this->items->id = $data->id;
        $this->items->nombre = $data->nombre;
        $this->items->apellido = $data->apellido;
        $this->items->email = $data->email;
        $this->items->password = password_hash($data->password, PASSWORD_DEFAULT);

        if ($this->items->updateUsuario()) {
            http_response_code(200);
            echo json_encode(["success" => "Usuario actualizado!"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Error al actualizar usuario!"]);
        }
    }

    //Eliminar un usuario
    public function delete()
    {
        header("Access-Control-Allow-Methods: DELETE");

        $data = json_decode(file_get_contents("php://input"));
        $this->items->id = $data->id;

        if ($this->items->deleteUsuario()) {
            http_response_code(200);
            echo json_encode(["success" => "Usuario eliminado!"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Error al eliminar usuario!"]);
        }
    }
}