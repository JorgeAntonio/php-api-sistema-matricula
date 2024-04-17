<?php
class User {
    //conexion con BD
    private $conn;
    //Tabla usuarios
    private $db_table = "users";
    //Columnas de la tabla usuarios
    public $id;
    public $username;
    public $password;
    public $email;
    public $role;
    public $photo;
    public $api_token;
    public $created_at;
    public $updated_at;
    //DB Connection
    public function __construct($db) {
        $this->conn = $db;
    }
    //Metodos de consulta
    //Traer todos los registros
    public function getUsers() {
        $sqlQuery = "SELECT id, username, password, email, role, 
                        photo, api_token, created_at, updated_at FROM " . $this->db_table ."";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }
    //Traer un solo registro por id
    public function getUser($id) {
        $sqlQuery = "SELECT id, username, password, email, role, 
        photo, api_token, created_at, updated_at FROM " . $this->db_table ." WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dataRow != null) {
            $this->username = $dataRow['username'];
            $this->password = $dataRow['password'];
            $this->email = $dataRow['email'];
            $this->role = $dataRow['role'];
            $this->photo = $dataRow['photo'];
            $this->api_token = $dataRow['api_token'];
            $this->created_at = $dataRow['created_at'];
            $this->updated_at = $dataRow['updated_at'];
        } else {
            return null;
        }
    }
    //Traer un solo registro por username (lo usa el login)
    public function getUserByUsername($username) {
        $sqlQuery = "SELECT id, username, password, api_token FROM " . $this->db_table ." WHERE username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(empty($dataRow)) {
            return null;
        }
        return $dataRow;
    }
    //Crear un nuevo registro
    public function createUser() {
        $sqlQuery = "INSERT INTO ". $this->db_table ." 
                    SET 
                        username = :username,
                        password = :password,
                        email = :email,
                        role = :role,
                        api_token = :api_token";
        $stmt = $this->conn->prepare($sqlQuery);
        //Sanitizar los datos
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->api_token = bin2hex(random_bytes(32));
        //bindear(enlazar) datos
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":api_token", $this->api_token);
        //Se ejecuta la consulta
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
        } catch(PDOException $e) {
            //return $e->getMessage();
            return false;
        }
    }
    //Actualizar un registro
    public function updateUser() {
        $sqlQuery = "UPDATE ". $this->db_table ." 
                    SET 
                        username = :username,
                        password = :password,
                        email = :email,
                        role = :role 
                    WHERE id = :id";
        $stmt = $this->conn->prepare($sqlQuery);
        //Sanitizar los datos
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->id = htmlspecialchars(strip_tags($this->id));
        //bindear(enlazar) datos
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":id", $this->id);
        //Se ejecuta la consulta
        try {
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            return false;
        }
        //$stmt->execute();
    }
    //Actualizar Api Token
    public function updateApiToken() {
        $sqlQuery = "UPDATE ". $this->db_table ." 
                    SET 
                        api_token = :api_token
                    WHERE
                        id = :id";
        $stmt = $this->conn->prepare($sqlQuery);
        //Sanitizar los datos
        $this->api_token = bin2hex(random_bytes(32));
        $this->id = htmlspecialchars(strip_tags($this->id));
        //bindear(enlazar) datos
        $stmt->bindParam(":api_token", $this->api_token);
        $stmt->bindParam(":id", $this->id);
        //Se ejecuta la consulta
        try {
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
    //Eliminar un registro
    public function deleteUser() {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        //Se ejecuta la consulta
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
    }
}