<?php
//session_start();
//// Verificar si hay una sesión activa
//if (!isset($_SESSION['student']) && !isset($_SESSION['student_token'])) {
//    header('Location: /usuarios/login'); // Redirigir a la página de inicio de sesión
//    exit;
//}
//
//include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/client-header.php';
//
////verificar si el usuario ya tiene un perfil para cargar sus cursos
//if (isset($_SESSION['student_id'])) {
//    $student_id = $_SESSION['student_id'];
//    $url = "http://lp4g3-api.test/api/v1/perfiles.php?id_usuario=" . $student_id;
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    $response = curl_exec($ch);
//    // Verificar errores en la petición cURL
//    if (curl_errno($ch)) {
//        echo 'Error: ' . curl_error($ch);
//    } else {
//        $perfil = json_decode($response, true);
////        echo '<pre>';
////        print_r($perfil);
////        echo '</pre>';
//    }
//    // Verificar si la decodificación de datos fue exitosa
//    if (json_last_error() !== JSON_ERROR_NONE) {
//        echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
//    }
//    // cerramos la sesión cURL
//    curl_close($ch);
//
//    // vista de cursos
//    if (!empty($perfil['semestre'])) {
//        // Hacer una solicitud a la API para obtener los cursos del semestre
//        $urlCursos = "http://lp4g3-api.test/api/v1/cursos.php?semestre=" . $perfil['semestre'];
//
//        $chCursos = curl_init();
//        curl_setopt($chCursos, CURLOPT_URL, $urlCursos);
//        curl_setopt($chCursos, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($chCursos, CURLOPT_CUSTOMREQUEST, 'GET');
//        curl_setopt($chCursos, CURLOPT_SSL_VERIFYPEER, false);
//
//        $responseCursos = curl_exec($chCursos);
//
//        if (curl_errno($chCursos)) {
//            echo 'Error: ' . curl_error($chCursos);
//        } else {
//            $cursos = json_decode($responseCursos, true);
//
//            // Verificar si se encontraron cursos
//            if (isset($cursos['body']) && !empty($cursos['body'])) {
//                echo '<div class="container">';
//                echo '<h1>Cursos Disponibles para Matrícula</h1>';
//                echo '<form id="create-matricula" action="/api/v1/matriculas.php" method="post">';
//                echo '<div class="cursos">';
//
//                foreach ($cursos['body'] as $curso) {
//                    echo '<div class="curso">';
//                    echo '<h2>' . htmlspecialchars($curso['nombre']) . '</h2>';
//                    echo '<p>' . htmlspecialchars($curso['descripcion']) . '</p>';
//                    echo '<p>Cupos Disponibles: ' . $curso['cupos'] . '</p>';
//                    // Agregar un checkbox para seleccionar el curso
//                    echo '<label><input type="checkbox" name="cursos[]" value="' . $curso['id'] . '"> Seleccionar</label>';
//                    echo '</div>';
//                }
//
//                // Agregar un campo oculto para id_usuario
//                echo '<input type="hidden" name="id_usuario" value="' . $student_id . '">';
//
//                // Agregar un botón para almacenar la matrícula en la base de datos
//                echo '<input class="button btnBlue" type="submit" value="Matricularse">';
//
//                echo '</div>';
//                echo '</form>';
//                echo '</div>';
//            } else {
//                echo '<div class="container">';
//                echo '<p>No se encontraron cursos para este semestre.</p>';
//                echo '</div>';
//            }
//        }
//
//        curl_close($chCursos);
//    } else {
//        echo '<div class="container">';
//        echo '<p>No se encontró información del semestre asociado al perfil.</p>';
//        echo '</div>';
//    }
//}
//
//include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/client-footer.php';
//


session_start();
// Verificar si hay una sesión activa
if (!isset($_SESSION['student']) && !isset($_SESSION['student_token'])) {
    header('Location: /usuarios/login'); // Redirigir a la página de inicio de sesión
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/client-header.php';

// Verificar si el usuario tiene un perfil
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    // Obtener el perfil del usuario
    $url_perfil = "http://lp4g3-api.test/api/v1/perfiles.php?id_usuario=" . $student_id;
    $ch_perfil = curl_init();
    curl_setopt($ch_perfil, CURLOPT_URL, $url_perfil);
    curl_setopt($ch_perfil, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_perfil, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch_perfil, CURLOPT_SSL_VERIFYPEER, false);

    $response_perfil = curl_exec($ch_perfil);

    if (curl_errno($ch_perfil)) {
        echo 'Error: ' . curl_error($ch_perfil);
    } else {
        $perfil = json_decode($response_perfil, true);
//        echo '<pre>';
//        print_r($perfil);
//        echo '</pre>';
    }

    curl_close($ch_perfil);

    if (!empty($perfil['semestre'])) {
        $semestreId = $perfil['semestre'];
        // Obtener cursos asociados al semestre del perfil del usuario
        $url_cursos = "http://lp4g3-api.test/api/v1/cursos.php?semestre=" . $semestreId;

        $ch_cursos = curl_init();
        curl_setopt($ch_cursos, CURLOPT_URL, $url_cursos);
        curl_setopt($ch_cursos, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_cursos, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch_cursos, CURLOPT_SSL_VERIFYPEER, false);

        $response_cursos = curl_exec($ch_cursos);

        if (curl_errno($ch_cursos)) {
            echo 'Error: ' . curl_error($ch_cursos);
        } else {
            $cursos = json_decode($response_cursos, true);
//            echo '<pre>';
//            print_r($cursos);
//            echo '</pre>';

            if (isset($cursos['body']) && !empty($cursos['body'])) {
                echo '<div class="container">';
                echo '<h1>Cursos Disponibles para Matrícula</h1>';
                echo '<form id="create-matricula" action="/api/v1/matriculas.php" method="post">';
                echo '<div class="cursos">';

                foreach ($cursos['body'] as $curso) {
                    echo '<div class="curso">';
                    echo '<h2>' . htmlspecialchars($curso['nombre']) . '</h2>';
                    echo '<p>' . htmlspecialchars($curso['descripcion']) . '</p>';
                    echo '<p>Cupos Disponibles: ' . $curso['cupos'] . '</p>';
                    // Agregar un checkbox para seleccionar el curso
                    echo '<label><input type="checkbox" name="cursos[]" value="' . $curso['id'] . '"> Seleccionar</label>';
                    echo '</div>';
                }

                // Agregar un campo oculto para id_usuario
                echo '<input type="hidden" name="id_usuario" value="' . $student_id . '">';

                // Agregar un botón para almacenar la matrícula en la base de datos
                echo '<input class="button btnBlue" type="submit" value="Matricularse">';

                echo '</div>';
                echo '</form>';
                echo '</div>';
            } else {
                echo '<div class="container">';
                echo '<p>No se encontraron cursos para este semestre.</p>';
                echo '</div>';
            }
        }

        curl_close($ch_cursos);
    } else {
        echo '<div class="container">';
        echo '<p>No se encontró información del semestre asociado al perfil.</p>';
        echo '</div>';
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/resources/views/layout/client-footer.php';

