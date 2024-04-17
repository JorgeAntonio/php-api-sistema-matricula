<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';
//Verificar la accion a realizar (create o update)
if(isset($_GET['id'])) {
    //Update user form
    $url = "http://lp4g3-api.test/api/v1/carreras.php?id=" .$_GET['id'];
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
        $carrera = json_decode($response, true);
//        echo '<pre>';
//        print_r($carrera);
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
    echo '<h1 class="title">Editar información de la Carrera</h1>';
    echo '<form id="form-update-carrera" action="" method="POST">';
    echo '<input type="hidden" name="id" value="'.htmlspecialchars($carrera['id']).'">';
    echo '<label for="nombre">Nombre de la Carrera:</label>';
    echo '<input type="text" id="nombre" name="nombre" value="'.htmlspecialchars($carrera['nombre']).'" required>';
    echo '<label for="descripcion">Descripción:</label>';
    echo '<input type="text" id="descripcion" name="descripcion" value="'.htmlspecialchars($carrera['descripcion']).'" required>';
    echo '<input type="submit" value="Actualizar">';
    echo '</form>';
    echo '</div>';
} else {
    //Formulario de registro
    echo '<div class="form-container">';
    echo '<h1 class="title">Registrar nueva Carrera</h1>';
    echo '<form id="form-create-carrera" action="" method="POST">';
    echo '<label for="nombre">Nombre de la Carrera:</label>';
    echo '<input type="text" id="nombre" name="nombre" required>';
    echo '<label for="descripcion">Descripción:</label>';
    echo '<input type="text" id="descripcion" name="descripcion" required>';
    echo '<input type="submit" value="Guardar">';
    echo '</form>';
    echo '</div>';
}

include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';