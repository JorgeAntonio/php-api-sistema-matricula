let formRegisterUsuarios = document.querySelector('#form-register-usuarios');
let formLoginUsuarios = document.querySelector('#form-login-usuarios');
let deleteEstudiantes = document.querySelectorAll('#del-student');
let formCreateStudent = document.querySelector('#form-create-student');
let formUpdateStudent = document.querySelector('#form-update-student');

if(formRegisterUsuarios) {
    registerUser(formRegisterUsuarios);
}
if(formLoginUsuarios) {
    loginUser(formLoginUsuarios);
}

deleteEstudiantes.forEach(function(deleteStudentBtn) {
    deleteStudent(deleteStudentBtn);
});

if(formCreateStudent) {
    createStudent(formCreateStudent);
}

if(formUpdateStudent) {
    updateStudent(formUpdateStudent);
}

function createStudent(createStudentForm) {
    createStudentForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        // console.log(json);

        fetch('http://lp4g3-api.test/api/v1/usuarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: json
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw 'Error en la llamada a la API';
                }
            })
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    window.location.href = '/usuarios';
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function updateStudent(updateStudentForm) {
    updateStudentForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        // console.log(json);

        fetch('http://lp4g3-api.test/api/v1/usuarios.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: json
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw 'Error en la llamada a la API';
                }
            })
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    window.location.href = '/usuarios';
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function loginUser(loginUserForm) {
    loginUserForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        // console.log(json);
        fetch('http://lp4g3-api.test/api/v1/usuarios.php?login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: json,
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    if (data.error.includes('Email')) {
                        document.getElementById('emailError').textContent = data.error;
                        document.getElementById('passwordError').textContent = '';
                    }
                    if(data.error.includes('Contraseña')) {
                        document.getElementById('passwordError').textContent = data.error;
                        document.getElementById('emailError').textContent = '';
                    }
                } else {
                    window.location.href = '/perfiles/perfil';
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function registerUser(registerUserForm) {
    registerUserForm.addEventListener('submit', function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        // console.log(json);

        fetch('http://lp4g3-api.test/api/v1/usuarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: json
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw 'Error en la llamada a la API';
                }
            })
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    window.location.href = '/usuarios/login';
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
}

function deleteStudent(deleteStudentBtn) {
    deleteStudentBtn.addEventListener('click', function(event) {
        event.preventDefault();

        let studentId = event.target.getAttribute('data-id');
        let object = {
            id: studentId
        };
        let json = JSON.stringify(object);
        fetch('http://lp4g3-api.test/api/v1/usuarios.php', {
            method: 'DELETE',
            body: json
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ocurrió un error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                window.location.href = '/usuarios';
            })
            .catch(error => {
                console.error(error);
            });
    });
}
