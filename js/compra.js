window.onload = iniciar;

function iniciar() {

    document.getElementById("btnCancelar").addEventListener("click", function () {
        window.history.back();
    });
    let formCompra = document.getElementById("formCompra");
    formCompra.addEventListener("submit", confirmarCompra)
    document.getElementById("retiroLocal").addEventListener("change", enviarRetirar);
    // NUEVO: Evento para el checkbox de pago en efectivo
    document.getElementById("pagoEfectivo").addEventListener("change", togglePagoEfectivo);
}

// Funcion para la logica de envío a domicilio o retiro por el local
function enviarRetirar() {
    let esRetiroLocal = this.checked;
    let camposEnvio = document.getElementById("camposEnvio");
    let infoLocal = document.getElementById("infoLocal");

    // Mostrar u ocultar campos de envío
    if (esRetiroLocal) {
        // Ocultar los campos de envío y hacerlos no requeridos
        camposEnvio.classList.add("d-none");
        document.getElementById("envioTitular").required = false;
        document.getElementById("envioCalle").required = false;
        document.getElementById("envioAltura").required = false;
        document.getElementById("envioPostal").required = false;
        // Mostrar la dirección y horario del local
        infoLocal.classList.replace("d-none", "d-block");
    } else {
        // Mostrar los campos de envío y hacerlos requeridos
        camposEnvio.classList.replace("d-none", "d-block");
        document.getElementById("envioTitular").required = true;
        document.getElementById("envioCalle").required = true;
        document.getElementById("envioAltura").required = true;
        document.getElementById("envioPostal").required = true;
        // Ocultar la dirección y horario del local
        infoLocal.classList.replace("d-block", "d-none");
    }
}
/* }*/
/*else {
    // Deshabilitar los botones de compra y carrito si no hay stock
    document.querySelector("#divStock h2").innerHTML = "SIN STOCK"
    divStock.style.backgroundColor = "#e72c2c"
    document.getElementById("btnCompra").disabled = true
    document.getElementById("btnCarrito").disabled = true
}*/

// NUEVO: función para el pago en efectivo
function togglePagoEfectivo() {
    let esPagoEfectivo = document.getElementById("pagoEfectivo").checked;
    let campoTarjeta = document.getElementById("tarjeta");

    if (esPagoEfectivo) {
        document.getElementById("tarjetaContainer").classList.add("d-none");
        campoTarjeta.required = false;
    } else {
        document.getElementById("tarjetaContainer").classList.remove("d-none");
        campoTarjeta.required = true;
    }
}

function confirmarCompra(event) {
    document.getElementById("envioTitular").classList.remove('is-invalid');
    document.getElementById("envioCalle").classList.remove('is-invalid');
    document.getElementById("envioAltura").classList.remove('is-invalid');
    document.getElementById("envioPostal").classList.remove('is-invalid');
    document.getElementById("envioNota").classList.remove('is-invalid');
    document.getElementById("dni").classList.remove('is-invalid');
    document.getElementById("tarjeta").classList.remove('is-invalid');
    event.preventDefault();
    if (validarDatos()) {
        let finCompraModal = new bootstrap.Modal(document.getElementById("modalFinCompra"));
        finCompraModal.show();
        formCompra.reset();
    }
}

function validarDatos() {
    let retiroLocal = document.getElementById("retiroLocal").checked;
    let pagoEfectivo = document.getElementById("pagoEfectivo").checked;
    let errores = 0;

    if (!retiroLocal) {
        //VALIDAR LARGO DEL NOMBRE
    let nombre = document.getElementById("envioTitular").value
    if(nombre.length < 3 || nombre.length > 50){
        document.getElementById("envioTitular").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR NOMBRE 2 PALABRAS
    let palabras = nombre.split(/ +/)
    if (palabras.length != 2) {
        document.getElementById("envioTitular").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR CARACTERES VALIDOS
    let exRegular = /^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/;
    if (!exRegular.test(nombre)) {
        document.getElementById("envioTitular").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR CALLE
    let calle = document.getElementById("envioCalle").value
    let exRegularCalle = /^[A-Za-z0-9.áéíóúÁÉÍÓÚ ]+$/;
    if (!exRegularCalle.test(calle)) {
        document.getElementById("envioCalle").classList.add('is-invalid');
        errores += 1
    }
    if (calle.length < 3 || calle.length > 30){
        document.getElementById("envioCalle").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR ALTURA
    let altura = document.getElementById("envioAltura").value
    if (isNaN(altura) || altura < 1 || altura > 99999) {
        document.getElementById("envioAltura").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR CODIGO POSTAL
    let cp = document.getElementById("envioPostal").value
    if (isNaN(cp) || cp.length != 4){
        document.getElementById("envioPostal").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR NOTAS
    let nota = document.getElementById("envioNota").value
    if (nota.length > 200){
        document.getElementById("envioNota").classList.add('is-invalid');
        errores += 1
    }
    }
    
    //VALIDAR DNI NUMERICO 
    let dni = document.getElementById("dni").value
    if (isNaN(dni)) {
        document.getElementById("dni").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR DNI ENTRE 100.000 Y 99.999.999
    if (dni < 100000 || dni > 99999999 || dni.length == 0) {
        document.getElementById("dni").classList.add('is-invalid');
        errores += 1
    }
    //VALIDAR TARJETA
    // NUEVO: Validación de la tarjeta solo si no es pago en efectivo
    let campoTarjeta = document.getElementById("tarjeta");
    if (!pagoEfectivo) {
        let tarjeta = campoTarjeta.value;
        if (tarjeta.length !== 12 || isNaN(tarjeta)) {
            campoTarjeta.classList.add('is-invalid');
            errores += 1;
        } else {
            campoTarjeta.classList.remove('is-invalid');
        }
        campoTarjeta.required = true; // Campo requerido si no es efectivo
    } else {
        campoTarjeta.required = false; // No requerido si es efectivo
        campoTarjeta.classList.remove('is-invalid'); // Limpieza de errores previos
    }

    console.log("Errores capturados: "+ errores)
    if (errores > 0){
        return false
    } else {
        return true
    }
}