window.onload = realizarCompra

function realizarCompra(/*idProcuto*/) {
    //const producto = productos.find(prod => prod.id === idProducto);
    /*if (producto.stock > 0) {*/

    // Funcion para la logica de envío a domicilio o retiro por el local
    document.getElementById("retiroLocal").addEventListener("change", function () {
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
    });

    let formCompra = document.getElementById("formCompra");
    formCompra.addEventListener("submit", function (event) {
        event.preventDefault();

        let finCompraModal = new bootstrap.Modal(document.getElementById("modalFinCompra"));
        finCompraModal.show();
    });
    /* }*/
    /*else {
        // Deshabilitar los botones de compra y carrito si no hay stock
        document.querySelector("#divStock h2").innerHTML = "SIN STOCK"
        divStock.style.backgroundColor = "#e72c2c"
        document.getElementById("btnCompra").disabled = true
        document.getElementById("btnCarrito").disabled = true
    }*/
}