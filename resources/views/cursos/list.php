<?php
session_start();
//verficar si hay una sesion activa
if(!isset($_SESSION['user']) && !isset($_SESSION['token'])) {
    header('Location: /users/login'); //ruta de login
    exit;
}

//Consumir el api rest
//cURL
$url = "http://lp4g3-api.test/api/v1/cursos.php";
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
    $cursos = json_decode($response, true);
//    echo '<pre>';
//    print_r($carreras);
//    echo '</pre>';
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

<h1 class="title">Lista de Cursos</h1>
<div class="tbl-container">
    <a class="button btnBlue" href="/cursos/create">Crear nuevo</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cupos</th>
            <th>Semestre</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach($cursos["body"] as $curso) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($curso['id']) .'</td>';
            echo '<td>' . htmlspecialchars($curso['nombre']) .'</td>';
            echo '<td>' . htmlspecialchars($curso['descripcion']) .'</td>';
            echo '<td>' . htmlspecialchars($curso['cupos']) .'</td>';
            echo '<td>' . htmlspecialchars($curso['id_semestre']) .'</td>';
            echo '<td>';
            echo '<a class="button btnYellow" href="/cursos/update?id='. htmlspecialchars($curso['id']) .'">Editar</a> ';
            echo '<a id="del-curso" class="button btnRed" href="#" data-id="'. htmlspecialchars($curso['id']) .'">Borrar</a>';
            echo '</td>';
        }
        ?>
    </table>
</div>
<?php
//Agregar pie de pagina HTML
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';
?>
