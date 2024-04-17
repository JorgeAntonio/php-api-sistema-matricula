<?php

class Perfil
{
    private $conn;
    private $db_table = "perfiles";
    public $id;
    public $id_usuario;
    public $carrera;
    public $semestre;
    public $perfil_token;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPerfiles()
    {
        $sqlQuery = "SELECT id, id_usuario, carrera, semestre, perfil_token,  created_at, updated_at FROM " . $this->db_table ."";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getPerfil($id)
    {
        $sqlQuery = "SELECT id, id_usuario, carrera, semestre, perfil_token, created_at, updated_at FROM " . $this->db_table ." WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dataRow != null) {
            $this->id_usuario = $dataRow['id_usuario'];
            $this->carrera = $dataRow['carrera'];
            $this->semestre = $dataRow['semestre'];
            $this->perfil_token = $dataRow['perfil_token'];
            $this->created_at = $dataRow['created_at'];
            $this->updated_at = $dataRow['updated_at'];
        } else {
            return null;
        }
    }

    public function getPerfilByIdUsuario($id_usuario)
    {
        $sqlQuery = "SELECT id, id_usuario, carrera, semestre, perfil_token,  created_at, updated_at FROM " . $this->db_table ." WHERE id_usuario = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id_usuario);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($dataRow)) {
            return null;
        }
        return $dataRow;
    }

    public function createPerfil()
    {
        $sqlQuery = "INSERT INTO ". $this->db_table ." SET
        id_usuario = :id_usuario,
        carrera = :carrera,
        semestre = :semestre,
        perfil_token = :perfil_token";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_usuario=htmlspecialchars(strip_tags($this->id_usuario));
        $this->carrera=htmlspecialchars(strip_tags($this->carrera));
        $this->semestre=htmlspecialchars(strip_tags($this->semestre));
        $this->perfil_token = bin2hex(openssl_random_pseudo_bytes(32));

        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":perfil_token", $this->perfil_token);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updatePerfil()
    {
        $sqlQuery = "UPDATE ". $this->db_table ."
        SET
        id_usuario = :id_usuario,
        carrera = :carrera,
        semestre = :semestre
        WHERE
        id = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_usuario=htmlspecialchars(strip_tags($this->id_usuario));
        $this->carrera=htmlspecialchars(strip_tags($this->carrera));
        $this->semestre=htmlspecialchars(strip_tags($this->semestre));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":semestre", $this->semestre);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deletePerfil()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
    }
}