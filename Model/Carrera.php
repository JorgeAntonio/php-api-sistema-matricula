<?php

class Carrera
{
    private $conn;
    private $db_table = "carreras";
    public $id;
    public $nombre;
    public $descripcion;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCarreras()
    {
        $sqlQuery = "SELECT id, nombre, descripcion, created_at, updated_at FROM " . $this->db_table ."";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getCarrera($id)
    {
        $sqlQuery = "SELECT id, nombre, descripcion, created_at, updated_at FROM " . $this->db_table ." WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dataRow != null) {
            $this->nombre = $dataRow['nombre'];
            $this->descripcion = $dataRow['descripcion'];
            $this->created_at = $dataRow['created_at'];
            $this->updated_at = $dataRow['updated_at'];
        } else {
            return null;
        }
    }

    public function createCarrera()
    {
        $sqlQuery = "INSERT INTO ". $this->db_table . " SET nombre = :nombre, descripcion = :descripcion";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateCarrera()
    {
        $sqlQuery = "UPDATE ". $this->db_table ." SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function deleteCarrera()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;

    }
}