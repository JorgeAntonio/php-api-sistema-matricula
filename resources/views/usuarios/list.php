<?php
session_start();
//verficar si hay una sesion activa
if(!isset($_SESSION['user']) && !isset($_SESSION['token'])) {
    header('Location: /users/login'); //ruta de login
    exit;
}

//Consumir el api rest
//cURL
$url = "http://lp4g3-api.test/api/v1/usuarios.php";
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
    $usuarios = json_decode($response, true);
//    echo '<pre>';
//    print_r($usuarios);
//    echo '</pre>';
}

//Verificar si la decodificacion de datos fue exitosa
if(json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
}

//cerramos la sesion cURL
curl_close($ch);

include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';

?>

<h1 class="title">Lista de estudiantes</h1>
<div class="tbl-container">
    <a class="button btnBlue" href="/usuarios/create">Crear nuevo</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach($usuarios["data"] as $usuario) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($usuario['id']) .'</td>';
            echo '<td>' . htmlspecialchars($usuario['nombre']) .'</td>';
            echo '<td>' . htmlspecialchars($usuario['apellido']) .'</td>';
            echo '<td>' . htmlspecialchars($usuario['email']) .'</td>';
            echo '<td>';
            echo '<a class="button btnYellow" href="/usuarios/update?id='. htmlspecialchars($usuario['id']) .'">Editar</a> ';
            echo '<a id="del-student" class="button btnRed" href="#" data-id="'. htmlspecialchars($usuario['id']) .'">Borrar</a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>

<?php
//Agregar pie de pagina
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';
?>
