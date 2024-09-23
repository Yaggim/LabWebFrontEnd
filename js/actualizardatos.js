window.onload = inicio

function inicio() {
    let formulario = document.getElementById("formulario")
    formulario.addEventListener("submit", actualizarDatos)
}

function actualizarDatos(event) {
    //LIMPIAR LEYENDAS DE ERRORES
    document.getElementById("telefono").classList.remove('is-invalid');
    document.getElementById("email").classList.remove('is-invalid');
    document.getElementById("password").classList.remove('is-invalid');
    document.getElementById("confirm_password").classList.remove('is-invalid');
    ////////////////////////////////////////////////////////////////////////
    event.preventDefault()
    if(validarFormulario()){
        alert("FORMULARIO VALIDADO OK!")//HAY QUE CAMBIARLO POR UN MODAL
        formulario.reset();
    }
    else{alert("ERROR AL ENVIAR FORMULARIO")/*QUITAR EL ELSE LUEGO, SOLO ES PARA CONTROL*/}
}

//IMPORTANTE: FALTA VALIDAR QUE NO ES OBLIGATORIO QUE MODIFIQUE LOS CAMPOS. SOLAMENTE MOSTRAR LEYENDAS SI EL INPUT FUE MODIFICADO
function validarFormulario(){
    //VALIDAR NUM TELEFÓNICO
    let tel = document.getElementById("telefono").value
    let errorTel = document.getElementById("errorTel");
    if (isNaN(tel)){
        errorTel.innerHTML = "Formato de teléfono inválido.";
        document.getElementById("telefono").classList.add('is-invalid');
        return false;
    }
    if (tel.length < 6 || tel.length > 14){
        errorTel.innerHTML = "El teléfono debe tener entre 6 y 14 números";
        document.getElementById("telefono").classList.add('is-invalid');
        return false;
    }

    //VALIDAR EMAIL
    let email = document.getElementById("email").value
    let exRegular = /^[A-Za-z0-9@._-]+$/;
    let errorEmail = document.getElementById("errorEmail")
    if (!exRegular.test(email)) {
        errorEmail.innerHTML = "Formato de Email inválido.";
        document.getElementById("email").classList.add('is-invalid');
        return false;
    }

    //VALIDAR NUEVA CONTRASEÑA
    let newPass = document.getElementById("password").value
    let errorPassword = document.getElementById("errorPassword")
    if(newPass.length < 8 || newPass.length > 20){
        errorPassword.innerHTML = "La contraseña debe tener entre 8 y 20 caracteres"
        document.getElementById("password").classList.add('is-invalid');
        return false;
    }

    //VALIDAR COINCIDENCIA DE NUEVA CONTRASEÑA
    let confirmPass = document.getElementById("confirm_password").value
    let errorConfirm = document.getElementById("errorConfirmPassword")
    if(confirmPass != newPass){
        errorConfirm.innerHTML = "Las contraseñas no coinciden"
        document.getElementById("confirm_password").classList.add('is-invalid');
        return false;
    }
    
    //VALIDAR VIEJA CONTRASEÑA
    //CUANDO HAYA UN BACKEND HECHO
    
    return true
}