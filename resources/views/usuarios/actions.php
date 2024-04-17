<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';
//Verificar la accion a realizar (create o update)
if(isset($_GET['id'])) {
    //Update user form
    $url = "http://lp4g3-api.test/api/v1/usuarios.php?id=". $_GET['id'];
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
        $usuario = json_decode($response, true);
//        echo '<pre>';
//        print_r($usuario);
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
    echo '<h1 class="title">Editar información del estudiante</h1>';
    echo '<form id="form-update-student" action="" method="POST">';
    echo '<input type="hidden" name="id" value="'.htmlspecialchars($usuario['id']).'">';
    echo '<label for="nombre">Nombre:</label>';
    echo '<input type="text" id="nombre" name="nombre" value="'.htmlspecialchars($usuario['nombre']).'" required>';
    echo '<label for="apellido">Apellido:</label>';
    echo '<input type="text" id="apellido" name="apellido" value="'.htmlspecialchars($usuario['apellido']).'" required>';
    echo '<label for="email">Email:</label>';
    echo '<input type="text" id="email" name="email" value="'.htmlspecialchars($usuario['email']).'" required>';
    echo '<label for="password">Contraseña:</label>';
    echo '<input type="password" id="password" name="password" required>';
    echo '<input type="submit"
    value="Actualizar">';
    echo '</form>';
    echo '</div>';
} else {
    //Formulario de registro
    echo '<div class="form-container">';
    echo '<h1 class="title">Registrar nuevo estudiante</h1>';
    echo '<form id="form-create-student" action="" method="POST">';
    echo '<label for="nombre">Nombre:</label>';
    echo '<input type="text" id="nombre" name="nombre" required>';
    echo '<label for="apellido">Apellido:</label>';
    echo '<input type="text" id="apellido" name="apellido" required>';
    echo '<label for="email">Email:</label>';
    echo '<input type="text" id="email" name="email" required>';
    echo '<label for="password">Contraseña:</label>';
    echo '<input type="password" id="password" name="password" required>';
    echo '<input type="submit" value="Registrar">';
    echo '</form>';
    echo '</div>';
}

//Agregar pie de pagina
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';