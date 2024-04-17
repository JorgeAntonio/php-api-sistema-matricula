let formCreateSemestre = document.querySelector('#form-create-semestre');
let formUpdateSemestre = document.querySelector('#form-update-semestre');
let deleteSemestreButton = document.querySelectorAll('#del-semestre');

if(formUpdateSemestre) {
    updateSemestre(formUpdateSemestre);
}

if(formCreateSemestre) {
    crearSemestre(formCreateSemestre);
}

deleteSemestreButton.forEach(function(deleteSemestreBtn) {
    deleteSemestre(deleteSemestreBtn);
});

function crearSemestre(createSemestreForm) {
    createSemestreForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/semestres.php', {
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
                window.location.href = '/semestres';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function updateSemestre(updateSemestreForm) {
    updateSemestreForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/semestres.php', {
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
                window.location.href = '/semestres';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function deleteSemestre(deleteSemestreBtn) {
    deleteSemestreBtn.addEventListener('click', function(event) {
        event.preventDefault();
        let semestreId = event.target.getAttribute('data-id');
        let object = {
            id: semestreId
        };

        let json = JSON.stringify(object);

        fetch('http://lp4g3-api.test/api/v1/semestres.php', {
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
                window.location.href = '/semestres';
            })
            .catch(error => {
                console.error(error);
            });
    });
}