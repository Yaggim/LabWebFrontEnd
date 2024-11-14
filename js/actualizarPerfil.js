
async function actualizarPerfil(event) {
    event.preventDefault();
    //LIMPIAR LEYENDAS DE ERRORES
    document.getElementById("telefono").classList.remove('is-invalid');
    document.getElementById("email").classList.remove('is-invalid');
    document.getElementById("password").classList.remove('is-invalid');
    document.getElementById("confirm_password").classList.remove('is-invalid');
    document.getElementById("oldPassword").classList.remove('is-invalid');

    const esValido = await validarFormulario();
    if (!esValido) return;

    const formData = new FormData(document.getElementById("formulario"));
    
    try {
        const basePath = window.location.pathname.split('/')[1];
        const response = await fetch(`/${basePath}/procesar-editar-perfil`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.redirect) {
            mostrarModal("Actualizar datos de tu cuenta", ["¡Datos actualizados exitosamente!", "Será redirigido/a a la página principal."], "Aceptar");

            // Redirecciona después de que el usuario cierre el modal
            document.getElementById("btnModal").addEventListener("click", () => {
                window.location.href = result.redirect;
            });

            document.getElementById("btnCerrar").addEventListener("click", () => {
                window.location.href = result.redirect;
            });
        } else if (result.error) {
            mostrarModal("Error", result.error, "Cerrar");
        }
    } catch (error) {
        mostrarModal("Error", "Error inesperado al procesar la solicitud", "Cerrar");
    }

}

async function validarFormulario(){
    //VALIDAR NUM TELEFÓNICO
    let validacion = true;
    let tel = document.getElementById("telefono").value;
    const telRegex = /^\d{2}-\d{4}-\d{4}$/;
    let errorTel = document.getElementById("errorTel");

    if (tel === "" || !telRegex.test(tel)){
        if (tel === "") {
            errorTel.textContent = "Debe completar el campo teléfono.";
        } else {
            errorTel.textContent = "El formato debe ser 11-1111-1111 y solo dígitos.";
        }
        errorTel.classList.add("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");
        document.getElementById("telefono").classList.add('is-invalid');
        validacion = false;
    }

    //VALIDAR EMAIL
    let email = document.getElementById("email").value;
    let exRegular = /^[A-Za-z0-9@._-]+$/;
    let errorEmail = document.getElementById("errorEmail");
    if (email === "" || !exRegular.test(email)) {
        errorEmail.textContent = "Formato de email inválido.";
        errorEmail.classList.add("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");
        document.getElementById("email").classList.add('is-invalid');
        validacion = false;
    }

    //VALIDAR NUEVA CONTRASEÑA
    let newPass = document.getElementById("password").value;
    const passRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;
    let errorPassword = document.getElementById("errorPassword");
    if(newPass === "" || newPass.length < 8 || newPass.length > 15 || !passRegex.test(newPass)){
        if (!passRegex.test(newPass)) {
            errorPassword.textContent = "La contraseña debe contener letras, números y al menos un símbolo.";
        } else {
            errorPassword.textContent = "La contraseña debe tener entre 8 y 15 caracteres";
        }
        errorPassword.classList.add("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");
        document.getElementById("password").classList.add('is-invalid');
        validacion = false;
    }

    //VALIDAR COINCIDENCIA DE NUEVA CONTRASEÑA
    let confirmPass = document.getElementById("confirm_password").value;
    let errorConfirm = document.getElementById("errorConfirmPassword");
    if((confirmPass !== newPass) || (confirmPass === "" && newPass === "")){
        errorConfirm.textContent = "Las contraseñas no coinciden";
        errorConfirm.classList.add("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");
        document.getElementById("confirm_password").classList.add('is-invalid');
        validacion = false;
    }

    // Validar la contraseña anterior en el backend
    const oldPass = document.getElementById("oldPassword").value;
    let errorOldPass = document.getElementById("errorOldPassword");
    if (oldPass === "") {
        errorOldPass.textContent = "Debe ingresar su contraseña anterior.";
        errorOldPass.classList.add("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");
        document.getElementById("oldPassword").classList.add('is-invalid');
        validacion = false;
    } else {
        const basePath = window.location.pathname.split('/')[1];
        const response = await fetch(`/${basePath}/validar-password`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ oldPassword: oldPass })
        });

        const result = await response.json();
        if (!result.success) {
            errorOldPass.textContent = result.message;
            errorOldPass.classList.add("text-danger-emphasis", "bg-danger-subtle", "border", "border-danger-subtle", "rounded-3");
            document.getElementById("oldPassword").classList.add('is-invalid');
            validacion = false;
        }
    }

    return validacion;
}

document.addEventListener("DOMContentLoaded", function() {
    const formulario = document.getElementById("formulario");
    if (formulario) {
        formulario.addEventListener("submit", actualizarPerfil);
    }
});
