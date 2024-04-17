let formCreatePerfil = document.querySelector('#form-perfil');

if(formCreatePerfil) {
    crearPerfil(formCreatePerfil);
}

function crearPerfil(createPerfilForm) {
    createPerfilForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        console.log(json);
        fetch('http://lp4g3-api.test/api/v1/perfiles.php', {
            method: 'POST',
            headers: {
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
                window.location.href = '/cursos/materias';
            })
            .catch(error => {
                console.error(error);
            });
    });
}

