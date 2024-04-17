let formCreateMatricula = document.querySelector('#create-matricula');

if(formCreateMatricula) {
    createMatricula(formCreateMatricula);
}

function createMatricula(createMatriculaForm) {
    createMatriculaForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let formData = new FormData(event.target);

        // Obtener los cursos seleccionados y convertirlos a un array de objetos JSON
        let selectedCourses = [];
        formData.getAll('cursos[]').forEach(courseId => {
            let course = {
                id: courseId,
                // Aquí puedes agregar más atributos del curso si son necesarios
            };
            selectedCourses.push(course);
        });

        // Agregar el ID de usuario
        let userId = formData.get('id_usuario');

        // Crear un objeto con los datos a enviar al servidor
        let data = {
            id_usuario: userId,
            cursos: selectedCourses
        };

        // Enviar los datos al servidor
        fetch('http://lp4g3-api.test/api/v1/matriculas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data), // Convertir el objeto a JSON
        })
            .then(response => {
                if(!response.ok) {
                    throw new Error('Ocurrió un error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                window.location.href = '/matriculas';
            })
            .catch(error => {
                console.error(error);
            });
    });
}
