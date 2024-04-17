<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Model/User.php';

class UserController {
    private $database;
    private $db;
    private $items;

    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->items = new User($this->db);
    }
    //Operaciones CRUD
    //Login User
    public function login() {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input"));

        if(!isset($data->username) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(["error" => "Debe proporcionar username y password"]);
            return;
        }
        //Consultar si existe el username
        $user = $this->items->getUserByUsername($data->username);
        if(!$user) {
            http_response_code(404);
            echo json_encode(["error" => "Username no existe!"]);
            return;
        }
        //Verificar la contraseña
        if(!password_verify($data->password, $user['password'])) {
            http_response_code(401);
            echo json_encode(["error" => "Password incorrecto!"]);
            return;
        }
        //Iniciar las sesion del usuario
        session_start();
        $_SESSION['user'] = $user['username'];
        $_SESSION['token'] = $user['api_token'];

        http_response_code(200);
        echo json_encode([
            'success' => 'true',
            'token' => $user['api_token']
        ]);
    }
    //Traer todos los usuarios
    public function read() {
        header("Access-Control-Allow-Methods: GET");

        $stmt = $this->items->getUsers();
        $itemCount = $stmt->rowCount();

        if($itemCount > 0) {
            $userData = array();
            $userData["data"] = array();
            $userData["itemCount"] = $itemCount;

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $u = array(
                    "id" => $id,
                    "username" => $username,
                    "password" => $password,
                    "email" => $email,
                    "role" => $role,
                    "photo" => $photo,
                    "api_token" => $api_token,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
                array_push($userData["data"], $u);
            }
            echo json_encode($userData);
        } else {
            http_response_code(404); //not found
            echo json_encode(
                array("message" => "No hay datos.")
            );
        }
    }
    //Traer un solo usuario por id
    public function readOne($id) {
        header("Access-Control-Allow-Methods: GET");

        $this->items->id = isset($_GET['id']) ? $_GET['id'] : die();
        $this->items->getUser($id);

        if($this->items->username != null) {
            //crear un array con los datos del usuario
            $user_data = array(
                "id" => $this->items->id,
                "username" => $this->items->username,
                "password" => $this->items->password,
                "email" => $this->items->email,
                "role" => $this->items->role,
                "photo" => $this->items->photo,
                "api_token" => $this->items->api_token,
                "created_at" => $this->items->created_at,
                "updated_at" => $this->items->updated_at
            );

            http_response_code(200); //ok
            echo json_encode($user_data);
        } else {
            http_response_code(404); //not found
            echo json_encode(array("message" => "Usuario no existe."));
        }
    }
    //Crear un usuario
    public function create() {
        header("Access-Control-Allow-Methods: POST");
        $data = json_decode(file_get_contents("php://input")); //body (json) de la peticion del cliente rest
        $this->items->username = $data->username;
        $this->items->password = $data->password;
        $this->items->email = $data->email;
        $this->items->role = $data->role;

        if($this->items->createUser()) {
            http_response_code(201); //ok
            echo json_encode(array("message" => "Usuario creado."));
        } else {
            http_response_code(503); //service unavailable
            echo json_encode(array("message" => "No se pudo crear el usuario."));
        }
    }
    //Actualizar un usuario
    public function update() {
        header("Access-Control-Allow-Methods: PUT");

        $data = json_decode(file_get_contents("php://input"));
        //el id de referencia para actualizar el usuario
        $this->items->id = $data->id;
        //valores de usuario para actualizar
        $this->items->username = $data->username;
        $this->items->password = $data->password;
        $this->items->email = $data->email;
        $this->items->role = $data->role;

        if($this->items->updateUser()) {
            http_response_code(200); //ok
            echo json_encode(array("message" => "Datos de Usuario actualizados."));
        } else {
            http_response_code(503); //service unavailable
            echo json_encode(array("message" => "No se pudo actualizar el usuario."));
        }
    }
    //Actualizar Api Token
    public function updateApiToken() {
        header("Access-Control-Allow-Methods: PUT");

        $data = json_decode(file_get_contents("php://input"));
        //el id de referencia para actualizar el usuario
        $this->items->id = $data->id;

        if($this->items->updateApiToken()) {
            http_response_code(200); //ok
            echo json_encode(array("message" => "Api Token de Usuario actualizado."));
        } else {
            http_response_code(503); //service unavailable
            echo json_encode(array("message" => "No se pudo actualizar el Api Token."));
        }
    }
    //Eliminar un usuario
    public function delete() {
        header("Access-Control-Allow-Methods: DELETE");

        $data = json_decode(file_get_contents("php://input"));
        //el id de referencia para actualizar el usuario
        $this->items->id = $data->id;

        if($this->items->deleteUser()) {
            http_response_code(200); //ok
            echo json_encode(array("message" => "Usuario eliminado."));
        } else {
            http_response_code(503); //service unavailable
            echo json_encode(array("message" => "No se pudo procesar la eliminación del usuario."));
        }
    }
}