<?php

class Usuario
{
    private $conn;
    private $db_table = "usuarios";
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $api_token;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUsuarios()
    {
        $sqlQuery = "SELECT id, nombre, apellido, email, password, api_token, created_at, updated_at FROM " . $this->db_table ."";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getUsuario($id)
    {
        $sqlQuery = "SELECT id, nombre, apellido, email, password, api_token, created_at, updated_at FROM " . $this->db_table ." WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dataRow != null) {
            $this->nombre = $dataRow['nombre'];
            $this->apellido = $dataRow['apellido'];
            $this->email = $dataRow['email'];
            $this->password = $dataRow['password'];
            $this->api_token = $dataRow['api_token'];
            $this->created_at = $dataRow['created_at'];
            $this->updated_at = $dataRow['updated_at'];
        } else {
            return null;
        }
    }

    public function getUsuarioByEmail($email)
    {
        $sqlQuery = "SELECT id, email, password, api_token FROM " . $this->db_table ." WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($dataRow)) {
            return null;
        }
        return $dataRow;
    }

    public function createUsuario()
    {
        $sqlQuery = "INSERT INTO " . $this->db_table . " SET nombre = :nombre, apellido = :apellido, email = :email, password = :password, api_token = :api_token";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->apellido=htmlspecialchars(strip_tags($this->apellido));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->api_token = bin2hex(openssl_random_pseudo_bytes(32));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":api_token", $this->api_token);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateUsuario()
    {
        $sqlQuery = "UPDATE " . $this->db_table . " SET nombre = :nombre, apellido = :apellido, email = :email, password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->apellido=htmlspecialchars(strip_tags($this->apellido));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateApiToken()
    {
        $sqlQuery = "UPDATE " . $this->db_table ." SET api_token = :api_token WHERE id = :id";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->api_token = bin2hex(openssl_random_pseudo_bytes(32));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":api_token", $this->api_token);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function deleteUsuario()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
    }
}