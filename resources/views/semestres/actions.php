<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';

//Consumir el api rest de carreras
$url_carreras = "http://lp4g3-api.test/api/v1/carreras.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_carreras);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response_carreras = curl_exec($ch);
//Verificar errores en la peticion cURL
if(curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    $carreras = json_decode($response_carreras, true);
}
//cerramos la sesion cURL
curl_close($ch);

//Verificar la accion a realizar (create o update)
if(isset($_GET['id'])) {
    //Update user form
    $url = "http://lp4g3-api.test/api/v1/semestres.php?id=" .$_GET['id'];
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
        $semestre = json_decode($response, true);
//        echo '<pre>';
//        print_r($semestre);
//        echo '</pre>';
    }
    //Verificar si la decodificacion de datos fue exitosa
    if(json_last_error() !== JSON_ERROR_NONE) {
        echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
    }
    //cerramos la sesion cURL
    curl_close($ch);
    //Formulario de actualizacion
    echo '<div class="form-container">';
    echo '<h1 class="title">Editar información del Semestre</h1>';
    echo '<form id="form-update-semestre" action="" method="POST">';
    echo '<input type="hidden" name="id" value="'.htmlspecialchars($semestre['id']).'">';
    echo '<label for="nombre">Nombre del Semestre:</label>';
    echo '<input type="text" id="nombre" name="nombre" value="'.htmlspecialchars($semestre['nombre']).'" required>';
    echo '<label for="carrera_id">Carrera:</label>';
    echo '<select id="carrera_id" name="carrera_id">';
    foreach($carreras["body"] as $carrera) {
        echo '<option value="' . htmlspecialchars($carrera['id']) . '">' . htmlspecialchars($carrera['id']) . ' - ' . htmlspecialchars($carrera['nombre']) . '</option>';
    }
    echo '</select>';
//    echo '<input type="text" id="carrera_id" name="carrera_id" value="'.htmlspecialchars($semestre['carrera_id']).'" required>';
    echo '<input type="submit" value="Actualizar">';
    echo '</form>';
    echo '</div>';
} else {
    //Consumir el api rest de carreras
    $url_carreras = "http://lp4g3-api.test/api/v1/carreras.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_carreras);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response_carreras = curl_exec($ch);
    //Verificar errores en la peticion cURL
    if(curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $carreras = json_decode($response_carreras, true);
    }
    //cerramos la sesion cURL
    curl_close($ch);

    //Formulario de registro
    echo '<div class="form-container">';
    echo '<h1 class="title">Registrar nuevo Semestre</h1>';
    echo '<form id="form-create-semestre" action="" method="POST">';
    echo '<label for="nombre">Nombre del Semestre:</label>';
    echo '<input type="text" id="nombre" name="nombre" required>';
    echo '<label for="carrera_id">Carrera:</label>';
    echo '<select id="carrera_id" name="carrera_id">';
    foreach($carreras["body"] as $carrera) {
        echo '<option value="' . htmlspecialchars($carrera['id']) . '">' . htmlspecialchars($carrera['id']) . ' - ' . htmlspecialchars($carrera['nombre']) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" value="Guardar">';
    echo '</form>';
    echo '</div>';
}

include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';

