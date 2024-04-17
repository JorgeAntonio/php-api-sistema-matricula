let formCreate = document.querySelector("#form-create-user");
let formUpdate = document.querySelector("#form-update-user");
let formLogin = document.querySelector("#form-login");
let deleteButtons = document.querySelectorAll("#del-user");

if(formCreate) {
    createUser(formCreate);
}
if(formUpdate) {
    updateUser(formUpdate);
}
if(formLogin) {
    loginUser(formLogin);
}
deleteButtons.forEach(function(deleteUserBtn) {
    deleteUser(deleteUserBtn);
});

function loginUser(loginUserForm) {
    loginUserForm.addEventListener("submit", function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        //console.log(json);
        fetch('http://lp4g3-api.test/api/v1/users.php?login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: json,
        })
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                console.error(data.error);
                if(data.error.includes('Username')) {
                    document.getElementById('usernameError').textContent = data.error;
                    document.getElementById('passwordError').textContent = '';
                }
                if(data.error.includes('Password')) {
                    document.getElementById('passwordError').textContent = data.error;
                    document.getElementById('usernameError').textContent = '';
                }
            } else {
                //login con éxito
                window.location.href = '/';
            }
        })
        .catch(error => {
            console.error(error);
        });
    });
}

function createUser(createUserForm) {
    createUserForm.addEventListener("submit", function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        //console.log(json);

        fetch('http://lp4g3-api.test/api/v1/users.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
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
            //formCreate.reset();
            window.location.href = '/users';
        })
        .catch(error => {
            console.error(error);
        });
    });
}

function updateUser(updateUserForm) {
    updateUserForm.addEventListener("submit", function(event) {
        event.preventDefault();

        let data = new FormData(event.target);
        let object = {};
        data.forEach(function(value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        //console.log(json);

        fetch('http://lp4g3-api.test/api/v1/users.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
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
            //formCreate.reset();
            window.location.href = '/users';
        })
        .catch(error => {
            console.error(error);
        });
    });
}

function deleteUser(deleteUserBtn) {
    deleteUserBtn.addEventListener("click", function(event) {
        event.preventDefault();
        
        let userId = event.target.getAttribute("data-id");
        let objeto = {
            id: userId
        };
        let json = JSON.stringify(objeto);

        fetch('http://lp4g3-api.test/api/v1/users.php', {
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
            window.location.href = '/users';
        })
        .catch(error => {
            console.error(error);
        });
    });
}