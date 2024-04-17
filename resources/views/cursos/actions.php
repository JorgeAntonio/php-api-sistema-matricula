<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';

// Consumir el API REST de semestres
$url_semestres = "http://lp4g3-api.test/api/v1/semestres.php";
$ch_semestres = curl_init();
curl_setopt($ch_semestres, CURLOPT_URL, $url_semestres);
curl_setopt($ch_semestres, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_semestres, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch_semestres, CURLOPT_SSL_VERIFYPEER, false);

$response_semestres = curl_exec($ch_semestres);

// Verificar errores en la petición cURL
if (curl_errno($ch_semestres)) {
    echo 'Error: ' . curl_error($ch_semestres);
} else {
    $semestres = json_decode($response_semestres, true);
//        echo '<pre>';
//        print_r($semestres);
//        echo '</pre>';
}

// Cerrar la sesión cURL
curl_close($ch_semestres);

//Verificar la accion a realizar (create o update)
if(isset($_GET['id'])) {
    //Update user form
    $url = "http://lp4g3-api.test/api/v1/cursos.php?id=" .$_GET['id'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    //Verificar errores en la peticion cURL
    if(curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $curso = json_decode($response, true);
//        echo '<pre>';
//        print_r($curso);
//        echo '</pre>';
    }
    //Verificar si la decodificacion de datos fue exitosa
    if(json_last_error() !== JSON_ERROR_NONE) {
        echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
    }
    //cerramos la sesion cURL
    curl_close($ch);

    //Formulario de actualizacion
    // Formulario de actualización
    echo '<div class="form-container">';
    echo '<h1 class="title">Editar información del Curso</h1>';
    echo '<form id="form-update-curso" action="" method="POST">';
    echo '<input type="hidden" name="id" value="'.htmlspecialchars($curso['id']).'">';
    echo '<label for="nombre">Nombre del Curso:</label>';
    echo '<input type="text" id="nombre" name="nombre" value="'.htmlspecialchars($curso['nombre']).'" required>';
    echo '<label for="descripcion">Descripción:</label>';
    echo '<input type="text" id="descripcion" name="descripcion" value="'.htmlspecialchars($curso['descripcion']).'" required>';
    echo '<label for="cupos">Cupos:</label>';
    echo '<input type="number" id="cupos" name="cupos" value="'.htmlspecialchars($curso['cupos']).'" required>';
    echo '<label for="id_semestre">Semestre:</label>';
    echo '<select type="text" id="id_semestre" name="id_semestre" required>';

    // Iterar sobre los semestres obtenidos del API y agregarlos como opciones
    foreach ($semestres["body"] as $semestre) {
        echo '<option value="' . htmlspecialchars($semestre['id']) . '">' . htmlspecialchars($semestre['nombre']) . '</option>';
    }

    echo '</select>';
    echo '<input type="submit" value="Actualizar">';
    echo '</form>';
    echo '</div>';
} else {
    // Formulario de registro
    echo '<div class="form-container">';
    echo '<h1 class="title">Registrar nuevo Curso</h1>';
    echo '<form id="form-create-curso" action="" method="POST">';
    echo '<label for="nombre">Nombre del Curso:</label>';
    echo '<input type="text" id="nombre" name="nombre" required>';
    echo '<label for="descripcion">Descripción:</label>';
    echo '<input type="text" id="descripcion" name="descripcion" required>';
    echo '<label for="cupos">Cupos:</label>';
    echo '<input type="number" id="cupos" name="cupos" required>';
    echo '<label for="id_semestre">Semestre:</label>';
    echo '<select type="text" id="id_semestre" name="id_semestre" required>';

    // Iterar sobre los semestres obtenidos del API y agregarlos como opciones
    foreach ($semestres["body"] as $semestre) {
        echo '<option value="' . htmlspecialchars($semestre['id']) . '">' . htmlspecialchars($semestre['nombre']) . '</option>';
    }

    echo '</select>';
    echo '<input type="submit" value="Guardar">';
    echo '</form>';
    echo '</div>';
}


include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';
