<?php

class Matricula
{
    private $conn;
    private $db_table = "matriculas";
    public $id;
    public $id_usuario;
    public $id_curso;
    public $fecha_matricula;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createMatricula()
    {
        $sqlQuery = "INSERT INTO " . $this->db_table . " SET id_usuario = :id_usuario, id_curso = :id_curso";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $this->id_curso = htmlspecialchars(strip_tags($this->id_curso));

        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":id_curso", $this->id_curso);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? true : false;
        } catch (Exception $e) {
            return false;
        }

    }

}