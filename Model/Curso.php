<?php

class Curso
{
    private $conn;
    private $db_table = "cursos";
    public $id;
    public $nombre;
    public $descripcion;
    public $cupos;
    public $id_semestre;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCursos()
    {
        $sqlQuery = "SELECT id, nombre, descripcion, cupos, id_semestre, created_at, updated_at FROM " . $this->db_table ."";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getCurso($id)
    {
        $sqlQuery = "SELECT id, nombre, descripcion, cupos, id_semestre, created_at, updated_at FROM " . $this->db_table ." WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if($dataRow != null) {
            $this->nombre = $dataRow['nombre'];
            $this->descripcion = $dataRow['descripcion'];
            $this->cupos = $dataRow['cupos'];
            $this->id_semestre = $dataRow['id_semestre'];
            $this->created_at = $dataRow['created_at'];
            $this->updated_at = $dataRow['updated_at'];
        } else {
            return null;
        }
    }

    public function getCursosBySemestreId($id_semestre)
    {
        $sqlQuery = "SELECT id, nombre, descripcion, cupos, id_semestre, created_at, updated_at FROM cursos WHERE id_semestre = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id_semestre);
        $stmt->execute();
        return $stmt;
    }


    public function createCurso()
    {
        $sqlQuery = "INSERT INTO ". $this->db_table ." SET nombre = :nombre, descripcion = :descripcion, cupos = :cupos, id_semestre = :id_semestre";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
        $this->cupos=htmlspecialchars(strip_tags($this->cupos));
        $this->id_semestre=htmlspecialchars(strip_tags($this->id_semestre));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":cupos", $this->cupos);
        $stmt->bindParam(":id_semestre", $this->id_semestre);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateCurso()
    {
        $sqlQuery = "UPDATE ". $this->db_table ."
        SET
        nombre = :nombre,
        descripcion = :descripcion,
        cupos = :cupos,
        id_semestre = :id_semestre
        WHERE
        id = :id";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
        $this->cupos=htmlspecialchars(strip_tags($this->cupos));
        $this->id_semestre=htmlspecialchars(strip_tags($this->id_semestre));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":cupos", $this->cupos);
        $stmt->bindParam(":id_semestre", $this->id_semestre);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function deleteCurso()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
    }
}