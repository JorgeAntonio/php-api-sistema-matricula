let formCreateCarrera = document.querySelector('#form-create-carrera');
let formUpdateCarrera = document.querySelector('#form-update-carrera');
let deleteButton = document.querySelectorAll('#del-carrera');

if(formUpdateCarrera) {
    updateCarrera(formUpdateCarrera);
}

if(formCreateCarrera) {
    crearCarrera(formCreateCarrera);
}

deleteButton.forEach(function(deleteCarreraBtn) {
    deleteCarrera(deleteCarreraBtn);
});

function crearCarrera(createCarreraForm) {
    createCarreraForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/carreras.php', {
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
                window.location.href = '/carreras';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function updateCarrera(updateCarreraForm) {
    updateCarreraForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/carreras.php', {
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
                window.location.href = '/carreras';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function deleteCarrera(deleteCarreraBtn) {
    deleteCarreraBtn.addEventListener('click', function(event) {
        event.preventDefault();
        let carreraId = event.target.getAttribute('data-id');
        let objecto = {
            id: carreraId
        };

        let json = JSON.stringify(objecto);

        fetch('http://lp4g3-api.test/api/v1/carreras.php', {
            method: 'DELETE',
            body: json
        }).then(response => {
                if(!response.ok) {
                    throw new Error('Ocurrió un error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                window.location.href = '/carreras';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

