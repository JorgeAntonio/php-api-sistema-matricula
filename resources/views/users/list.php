<?php
session_start();
//verficar si hay una sesion activa
if(!isset($_SESSION['user']) && !isset($_SESSION['token'])) {
    header('Location: /users/login'); //ruta de login
    exit;
}
//Consumir el api rest
//cURL
$url = "http://lp4g3-api.test/api/v1/users.php";
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
    $users = json_decode($response, true);
}
//Verificar si la decodificacion de datos fue exitosa
if(json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
}
//cerramos la sesion cURL
curl_close($ch);
//Agregar encabezado HTML
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';
?>
<h1 class="title">Lista de Usuarios</h1>
<div class="tbl-container">
    <a class="button btnBlue" href="/users/create">Crear nuevo</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Foto</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach($users["data"] as $user) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($user['id']) .'</td>';
            echo '<td>' . htmlspecialchars($user['username']) .'</td>';
            echo '<td>' . htmlspecialchars($user['email']) .'</td>';
            echo '<td>' . htmlspecialchars($user['role']) .'</td>';
            echo '<td><img src="' . htmlspecialchars($user['photo']) .'" width="50"></td>';
            echo '<td>';
            echo '<a class="button btnYellow" href="/users/update?id='. htmlspecialchars($user['id']) .'">Editar</a> ';
            echo '<a id="del-user" class="button btnRed" href="#" data-id="'. htmlspecialchars($user['id']) .'">Borrar</a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>
<?php
//Agregar pie HTML
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';
?>