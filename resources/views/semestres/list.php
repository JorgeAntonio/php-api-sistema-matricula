<?php
session_start();
//verficar si hay una sesion activa
if(!isset($_SESSION['user']) && !isset($_SESSION['token'])) {
    header('Location: /users/login'); //ruta de login
    exit;
}

//Consumir el api rest
//cURL
$url = "http://lp4g3-api.test/api/v1/semestres.php";
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
    $semestres = json_decode($response, true);
//    echo '<pre>';
//    print_r($semestres);
//    echo '</pre>';
}

//Verificar si la decodificacion de datos fue exitosa
if(json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
}

//cerramos la sesion cURL
curl_close($ch);

// Consumir el API REST de carreras para obtener el nombre de la carrera
$url_carreras = "http://lp4g3-api.test/api/v1/carreras.php";
$ch_carreras = curl_init();
curl_setopt($ch_carreras, CURLOPT_URL, $url_carreras);
curl_setopt($ch_carreras, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_carreras, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch_carreras, CURLOPT_SSL_VERIFYPEER, false);

$response_carreras = curl_exec($ch_carreras);

// Verificar errores en la petición cURL
if(curl_errno($ch_carreras)) {
    echo 'Error: ' . curl_error($ch_carreras);
} else {
    $carreras = json_decode($response_carreras, true);
}

// Verificar si la decodificación de datos fue exitosa
if(json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
}

// Cerrar la sesión cURL
curl_close($ch_carreras);

//Agregar encabezado HTML
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/header.php';
?>

<h1 class="title">Lista de Semestres</h1>
<div class="tbl-container">
    <a class="button btnBlue" href="/semestres/create">Crear nuevo</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Carrera</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach($semestres["body"] as $semestre) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($semestre['id']) .'</td>';
            echo '<td>' . htmlspecialchars($semestre['nombre']) .'</td>';
            // Buscar el nombre de la carrera correspondiente al ID
            $carrera_nombre = '';
            foreach($carreras["body"] as $carrera) {
                if($carrera['id'] == $semestre['carrera_id']) {
                    $carrera_nombre = $carrera['nombre'];
                    break;
                }
            }
            echo '<td>' . htmlspecialchars($carrera_nombre) .'</td>';
            echo '<td>';
            echo '<a class="button btnYellow" href="/semestres/update?id='. htmlspecialchars($semestre['id']) .'">Editar</a> ';
            echo '<a id="del-semestre" class="button btnRed" href="#" data-id="'. htmlspecialchars($semestre['id']) .'">Borrar</a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>

<?php
//Agregar pie de pagina HTML
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/footer.php';
?>
