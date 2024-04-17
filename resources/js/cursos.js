let formCreateCurso = document.querySelector('#form-create-curso');
let formUpdateCurso = document.querySelector('#form-update-curso');
let deleteCursoButton = document.querySelectorAll('#del-curso');

if(formUpdateCurso) {
    updateCurso(formUpdateCurso);
}

if(formCreateCurso) {
    crearCurso(formCreateCurso);
}

deleteCursoButton.forEach(function(deleteCursoBtn) {
    deleteCurso(deleteCursoBtn);
});

function crearCurso(createCursoForm) {
    createCursoForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/cursos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: json,
        })
            .then(response => {
                if(!response.ok) {
                    throw new Error('Ocurrió un error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                window.location.href = '/cursos';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function updateCurso(updateCursoForm) {
    updateCursoForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/cursos.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: json,
        })
            .then(response => {
                if(!response.ok) {
                    throw new Error('Ocurrió un error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                window.location.href = '/cursos';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function deleteCurso(deleteCursoBtn) {
    deleteCursoBtn.addEventListener('click', function(event) {
        event.preventDefault();

        let cursoId = event.target.getAttribute('data-id');
        let object = {
            id: cursoId
        };
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/cursos.php', {
            method: 'DELETE',
            body: json
        })
            .then(response => {
                if(!response.ok) {
                    throw new Error('Ocurrió un error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                window.location.href = '/cursos';
            })
            .catch(error => {
                console.error(error);
            });
    });
}