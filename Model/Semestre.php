<?php

class Semestre
{
    private $conn;
    private $db_table = "semestres";
    public $id;
    public $nombre;
    public $carrera_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getSemestres()
    {
        $sqlQuery = "SELECT id, nombre, carrera_id FROM " . $this->db_table ."";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getSemestre($id)
    {
        $sqlQuery = "SELECT id, nombre, carrera_id FROM " . $this->db_table ." WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dataRow != null) {
            $this->nombre = $dataRow['nombre'];
            $this->carrera_id = $dataRow['carrera_id'];
        } else {
            return null;
        }
    }

    public function createSemestre()
    {
        $sqlQuery = "INSERT INTO ". $this->db_table ." SET nombre = :nombre, carrera_id = :carrera_id";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->carrera_id=htmlspecialchars(strip_tags($this->carrera_id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":carrera_id", $this->carrera_id);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateSemestre()
    {
        $sqlQuery = "UPDATE ". $this->db_table ." SET nombre = :nombre, carrera_id = :carrera_id WHERE id = :id";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->carrera_id=htmlspecialchars(strip_tags($this->carrera_id));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":carrera_id", $this->carrera_id);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function deleteSemestre()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
    }
}