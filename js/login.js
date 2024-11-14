
async function validarLogin(e){
    e.preventDefault();

    // Limpiar mensajes de error anteriores
    const errorLoginUsername = document.getElementById("errorLoginUsername");
    const errorLoginPassword = document.getElementById("errorLoginPassword");
    const nombreUsuarioElement = document.getElementById("nombreUsuario");
    const passwordElement = document.getElementById("passwordSesion");

    nombreUsuarioElement.classList.remove('is-invalid');
    passwordElement.classList.remove('is-invalid');
    errorLoginUsername.textContent = "";
    errorLoginPassword.textContent = "";

    const username = document.getElementById("nombreUsuario");
    const password = document.getElementById("passwordSesion");
    let validacion = true;

    // Validación frontend
    if (username.value === "") {
        errorLoginUsername.textContent = "Debe ingresar el nombre de usuario.";
        document.getElementById("nombreUsuario").classList.add("is-invalid");
        validacion = false;
    }

    if (password.value === "") {
        errorLoginPassword.textContent = "Debe ingresar la contraseña.";
        document.getElementById("passwordSesion").classList.add("is-invalid");
        validacion = false;
    }

    if (!validacion) return;

    // Enviar datos al backend
    try {
        const basePath = window.location.pathname.split('/')[1];
        const response = await fetch(`/${basePath}/procesar-login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: 'login', username: username.value, password: password.value })
        });

        const result = await response.json();
        
        if (result.success) {
            document.querySelector('.form-login').innerHTML = result.html;
            
            const btnPerfil = document.getElementById('btnPerfil');
            if (btnPerfil) {
                btnPerfil.addEventListener('click', function() {
                    const basePath = window.location.pathname.split('/')[1];
                    window.location.href = `/${basePath}/perfil`;
                });
            }
            
            const botonesEditarPerfil = document.getElementsByClassName('btnEditarPerfil');
            Array.from(botonesEditarPerfil).forEach((boton) => {
                boton.addEventListener('click', function () {
                    const basePath = window.location.pathname.split('/')[1];
                    window.location.href = `/${basePath}/editar-perfil`;
                });
            });

            location.reload();
        } else {
            // Mostrar mensaje de error del backend
            if (result.error === "Usuario incorrecto") {
                errorLoginUsername.textContent = "Nombre de usuario incorrecto.";
                document.getElementById("nombreUsuario").classList.add("is-invalid");
            } else if (result.error === "Contraseña incorrecta") {
                errorLoginPassword.textContent = "Contraseña incorrecta.";
                document.getElementById("passwordSesion").classList.add("is-invalid");
            } else {
                mostrarModal("Error", [`Error inesperado: ${result.error}`, "Inténtalo de nuevo."], "Aceptar");
            }
        }
    } catch (error) {
        mostrarModal("Error", [`Error en la solicitud de inicio de sesión: ${error}.`, "Inténtelo más tarde."], "Cerrar");
    }
}

async function realizarLogout(e) {
    try {
        e.preventDefault();
        const basePath = window.location.pathname.split('/')[1];
        const response = await fetch(`/${basePath}/procesar-login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'logout' })
        });

        const result = await response.json();

        if (result.success) {
            document.querySelector('.form-login').innerHTML = result.html;
            location.reload();
        } else {
            mostrarModal("Error", result.error, "Aceptar");
        }
    } catch (error) {
        mostrarModal("Error", [`Error en la solicitud de logout: ${error}.`, "Inténtelo más tarde."], "Cerrar");
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.querySelector(".form-login");
    if (formLogin) {
        formLogin.addEventListener("submit", (e) => {
            const action = e.submitter.value;
            if (action === "login") {
                validarLogin(e);
            } else if (action === "logout") {
                realizarLogout(e);
            }
        });
    }
});
