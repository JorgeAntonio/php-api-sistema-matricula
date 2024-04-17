<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once $_SERVER['DOCUMENT_ROOT'] . '/Config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Model/Matricula.php';

class MatriculaController
{
    private $database;
    private $db;
    private $items;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->items = new Matricula($this->db);
    }

    public function create()
    {
        // Establecer los métodos permitidos
        header("Access-Control-Allow-Methods: POST");

        // Obtener los datos enviados desde el cliente en formato JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Verificar si se proporcionaron los datos necesarios
        if (!isset($data['id_usuario']) || !isset($data['cursos'])) {
            http_response_code(400);
            echo json_encode(["error" => "Debe proporcionar id_usuario y cursos"]);
            return;
        }

        // Extraer el ID del usuario y los cursos seleccionados
        $id_usuario = $data['id_usuario'];
        $cursos = $data['cursos'];

        // Iterar sobre los cursos y crear la matrícula para cada uno
        foreach ($cursos as $curso) {
            $id_curso = $curso['id'];

            // Asignar los valores a la instancia de Matricula
            $this->items->id_usuario = $id_usuario;
            $this->items->id_curso = $id_curso;

            // Crear la matrícula en la base de datos
            if (!$this->items->createMatricula()) {
                http_response_code(503);
                echo json_encode(["error" => "Error al crear matrícula"]);
                return;
            }
        }

        // Si se crearon todas las matrículas con éxito, devolver una respuesta exitosa
        http_response_code(201);
        echo json_encode(["success" => "Matrícula(s) creada(s)"]);

    }
}