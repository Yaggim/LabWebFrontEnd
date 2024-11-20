
let errores = {};
let hayErrores;
let pass;

async function manejadorDeValidaciones(e) {
    e.preventDefault();
    limpiarErrores();
    hayErrores = false;

    errores.error_nombre = validarNombreOApellido(1);
    errores.error_apellido = validarNombreOApellido(2);
    errores.error_dni = validarDni();
    errores.error_tel = validarTelefono();
    errores.error_user = validarUser();
    errores.error_email = validarEmail();
    errores.error_pass = validarPass();
    errores.error_pass_conf = validarPassConf();
    errores.error_terminos = validarTerminos();

    renderDeErrores();

    if (hayErrores) return;

    const formData = new FormData(document.getElementById("form"));
    
    try {
        const basePath = window.location.pathname.split('/')[1];
        const response = await fetch(`/${basePath}/procesar-registro`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.redirect) {
            // Muestra el modal con un mensaje de éxito
            mostrarModal("Registro de nuevo usuario", ["¡Gracias por registrarte en nuestra plataforma!", "Tu cuenta ha sido creada con éxito.", "Serás redirigido/a a la página principal."], "Aceptar");

            // Redirecciona después de que el usuario cierre el modal
            document.getElementById("btnModal").addEventListener("click", () => {
                window.location.href = result.redirect;
            });

            document.getElementById("btnCerrar").addEventListener("click", () => {
                window.location.href = result.redirect;
            });
        } else if (result.error) {
            // Muestra el modal con un mensaje de error
            mostrarModal("Error", result.error, "Cerrar");
        }
    } catch (error) {
        mostrarModal("Error", "Error inesperado al procesar la solicitud", "Cerrar");
    }

}

function renderDeErrores() {
    for (let clave in errores) {
        if (errores.hasOwnProperty(clave)) {
            if (errores[clave] != null) {
                hayErrores = true;

                const renderDivError = document.getElementById(clave);
                renderDivError.textContent = errores[clave];
                renderDivError.classList.add("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");

                const hermanoDiv = renderDivError.previousElementSibling;
                hermanoDiv.classList.remove("is-valid");
                hermanoDiv.classList.add("is-invalid");
            }
        }
    }
}


function limpiarErrores() {
    let divError = document.querySelectorAll(".errores-registro");

    for (let elemento of divError) {
        elemento.textContent = "";
        elemento.classList.remove("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");

        const hermanoDiv = elemento.previousElementSibling;
        hermanoDiv.classList.remove("is-invalid");
        hermanoDiv.classList.add("is-valid");
    }
}


function validarNombreOApellido(caso) {
    let campoNombreOApellido;

    if (caso == 1) {
        campoNombreOApellido = String(document.getElementById("nombre").value);
    } else {
        campoNombreOApellido = String(document.getElementById("apellido").value);
    }

    const nombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ]+$/;
    const splitCampo = campoNombreOApellido.split(' ');

    if (campoNombreOApellido.length > 40) {
        return "El máximo de caracteres es 40.";
    }

    if (campoNombreOApellido == "") {
        return "Debe completar este campo.";
    }

    for (palabra of splitCampo) {
        if (!nombreRegex.test(palabra)) {
            return "El campo debe contener solo caracteres alfabéticos.";
        }
    }

    return null;
}


function validarDni() {
    let campoDni = String(document.getElementById("dni").value);
    let dniRegex = /^\d+$/;
    let dniNum = parseInt(campoDni);

    if (campoDni == "") {
        return "Debe completar el campo DNI.";
    }

    if (!dniRegex.test(campoDni)) {
        return "Solo caracteres numéricos.";
    }

    if (dniNum < 3000000 || dniNum > 99999999) {
        return "Solo DNI entre 3.000.000 y 99.999.999.";
    }

    return null;
}


function validarTelefono() {
    const campoTel = String(document.getElementById("telefono").value);
    const telRegex = /^\d{2}-?\d{4}-?\d{4}$/;

    if (campoTel == "") {
        return "Debe completar el campo teléfono.";
    }

    if (!telRegex.test(campoTel)) {
        return "El formato debe ser 11-1111-1111 y solo diez (10) dígitos.";
    }

    return null;
}


function validarUser() {
    const campoUser = String(document.getElementById("username").value);
    const userRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;

    if (campoUser == "") {
        return "Debe completar el nombre de usuario.";
    }
    if (campoUser.length < 8 || campoUser > 15) {
        return "El nombre de usuario debe tener entre 8 y 15 caracteres.";
    }
    if (!userRegex.test(campoUser)) {
        return "El nombre de usuario debe contener letras, números y al menos un símbolo (@$!%*?&).";
    }

    return null;
}


function validarEmail() {
    const campoEmail = String(document.getElementById("email").value);
    const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

    if (campoEmail == "") {
        return "Debe completar el campo email.";
    }

    if (!emailRegex.test(campoEmail)) {
        return "El email debe ser de la forma example@email.com.";
    }

    return null;
}


function validarPass() {
    const campoPass = String(document.getElementById("password").value);
    const passRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;

    if (campoPass == "") {
        return "Debe completar el campo contraseña.";
    }

    if (campoPass.length < 8 || campoPass.length > 15) {
        return "La contraseña debe tener entre 8 y 15 caracteres.";
    }

    if (!passRegex.test(campoPass)) {
        return "La contraseña debe contener letras, números y al menos un símbolo (@$!%*?&).";
    }

    pass = campoPass;

    return null;
}


function validarPassConf() {
    let campoPass = String(document.getElementById("confirm_password").value);

    if (campoPass == "") {
        return "Debe completar el campo confirmar contraseña.";
    }

    if (pass != campoPass) {
        return "La contraseña no coincide.";
    }

    return null;
}


function validarTerminos() {
    let terminos = document.getElementById("terminos");

    if (!terminos.checked) {
        return "Debe aceptar los términos y condiciones.";
    }

    return null;
}


document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("form");
    if (formulario) {
        formulario.addEventListener("submit", manejadorDeValidaciones);
    }
});