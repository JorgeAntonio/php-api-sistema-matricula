<?php
session_start();
//verficar si hay una sesion activa
if(!isset($_SESSION['student']) && !isset($_SESSION['student_token'])) {
    header('Location: /usuarios/login'); //ruta de login
    exit;
}

// consumir el api rest de perfil para verificar que el estudiante ya tiene perfil
$curl = "http://lp4g3-api.test/api/v1/perfiles.php?id_usuario=" .$_SESSION['student_id'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $curl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
if(curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    $users = json_decode($response, true);
//    echo '<pre>';
//    print_r($users);
//    echo '</pre>';
}
//Verificar si la decodificacion de datos fue exitosa
if(json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
}
//cerramos la sesion cURL
curl_close($ch);

// si el estudiante ya tiene perfil, redirigir a la página de perfil
error_reporting(E_ERROR | E_PARSE);
if ($users['perfil_token'] != null) {
    header('Location: /cursos/materias');
    exit;
}


include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/login-header.php';

// Consumir el API REST de carreras
$url_carreras = "http://lp4g3-api.test/api/v1/carreras.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_carreras);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response_carreras = curl_exec($ch);

// Verificar errores en la petición cURL
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    $carreras = json_decode($response_carreras, true);
}

// Cerrar la sesión cURL
curl_close($ch);

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
}

// Cerrar la sesión cURL
curl_close($ch_semestres);


?>

<div>
    <a class="button cerrar" href="/usuarios/logout">Cerrar sesión</a>
    <h1 class="title">Perfil</h1>
    <form id="form-perfil" action="" method="post">
        <label for="carrera">Carrera:</label>
        <select name="carrera" id="carrera">
            <?php
            // Iterar sobre las carreras obtenidas del API y agregarlas como opciones
            foreach ($carreras["body"] as $carrera) {
                echo '<option value="' . htmlspecialchars($carrera['id']) . '">' . htmlspecialchars($carrera['nombre']) . '</option>';
            }
            ?>
        </select>
        <span id="carreraError" style="color: red;"></span>
        <label for="semestre">Semestre:</label>
        <select name="semestre" id="semestre">
            <?php
            // Iterar sobre los semestres obtenidos del API y agregarlos como opciones
            foreach ($semestres["body"] as $semestre) {
                echo '<option value="' . htmlspecialchars($semestre['id']) . '">' . htmlspecialchars($semestre['nombre']) . '</option>';
            }
            ?>
        </select>
        <span id="semestreError" style="color: red;"></span>
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['student_id']; ?>">
        <input type="submit" value="Guardar perfil">
    </form>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/client-footer.php';
?>

