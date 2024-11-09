var producto = [];

async function fetchProducts(id = null) {
    const url = `../../includes/admin/producto.php?id_producto=${id}`;
    const response = await fetch(url);
    return response.json();
}

async function loadProducts(productId) {
    try {
        const fetchProductos = await fetchProducts(productId);
        producto = fetchProductos;
        cargarProductoPorID(productId);
    } catch (error) {
        console.error('Error cargando los productos en (loadProducts):', error);
    }
}

// FUNCION DE LOADCOMBO(COMBOID)

async function fetchDolarBlue() {
    const response = await fetch("https://dolarapi.com/v1/dolares/blue");
    const data = await response.json();
    dolarBlue = data.venta;
    return dolarBlue;
}

async function actualizarPrecioARS() {
    try {
        const dolarBlue = await fetchDolarBlue();
        const precioEnPesos = producto.precio_usd * dolarBlue;
        console.log("Valor actual del Dolar Blue: ", dolarBlue);
        document.getElementById("producto-priceARS").textContent = "Precio: US$" + producto.precio_usd + "  |  $" + precioEnPesos.toFixed(2);
    } catch (error) {
        console.error("Error al obtener el precio en pesos:", error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const urlPath = window.location.pathname;
    const segmentosPath = urlPath.split('/');
    const productId = parseInt(segmentosPath[segmentosPath.length - 1]);

    if (productId) {
        loadProducts(productId);
    } else {
        console.error("ID del producto no encontrado en la URL.");
    }
});

function cargarProductoPorID(idProducto) {
    console.log("PRODUCTO CARGADO:", producto);
    if (producto) {
        completarDatosPagina()
        completarRecuadroStock()

        // Incremento y decremento de cantidad seleccionada del producto
        const decrement = document.getElementById('btnDecrement');
        const increment = document.getElementById('btnIncrement');
        let cantidad = document.getElementById('quantity');

        decrement.addEventListener('click', function decrementar() {
            let valorActual = parseInt(cantidad.value);
            if (valorActual > 1) {
                cantidad.value = valorActual - 1;
            }
        });
        increment.addEventListener('click', function incrementar() {
            let valorActual = parseInt(cantidad.value);
            if (valorActual < producto.stock) {
                cantidad.value = valorActual + 1;
            }
        });

        // Comprar producto
        /*
        document.getElementById("btnCompra").addEventListener("click", function () {
            // Vuelvo a completar el recuadro de stock, por si bajaron las unidades en stock
            document.getElementById("pagoTotal").innerHTML = "Cant: " + cantidad.value + "<br>Total a abonar $" + cantidad.value * (producto.precio_usd);
            realizarCompra(idProducto);
        });
        
        document.getElementById("btnCompra").addEventListener("click", function () {
            // Redirige a la página de pago
            window.location.href = "../../views/compra.php";      
        });
        
         ESTA LÓGICA AHORA DEBE ESTAR APLICADA A LA PÁGINA DE COMPRA
        // Formulario de datos del modal envío a domicilio
        document.getElementById("formCompra").addEventListener("submit", function (event) {
            document.getElementById("envioTitular").classList.remove('is-invalid');
            document.getElementById("envioCalle").classList.remove('is-invalid');
            document.getElementById("envioAltura").classList.remove('is-invalid');
            document.getElementById("envioPostal").classList.remove('is-invalid');
            document.getElementById("envioNota").classList.remove('is-invalid');
            document.getElementById("dni").classList.remove('is-invalid');
            document.getElementById("tarjeta").classList.remove('is-invalid');
            event.preventDefault();
            if (validarDatos()) {
                // Descuenta stock al producto
                producto.stock -= cantidad.value;
                // Solo para control de los campos ////////////////////////////////////////////////
                console.log("Stock actual del producto: " + producto.stock)
                let dni = document.getElementById("envioTitular").value;
                let titular = document.getElementById("envioTitular").value;
                let calle = document.getElementById("envioCalle").value;
                let altura = document.getElementById("envioAltura").value;
                let postal = document.getElementById("envioPostal").value;
                let nota = document.getElementById("envioNota").value;
                let pago = document.getElementById("tarjeta").value;
                let articulo = [producto.brand_name, producto.model_name, producto.id_producto];
                console.log("DNI: ", dni, "Titular:", titular, "Calle:", calle, "Altura:", altura, "Código Postal:", postal, "Nota:", nota, "Pago efectuado:", pago, "Prod:", articulo, "Cantidad:", cantidad.value);
                ////////////////////////////////////////////////////////////////////////////////////
                // Setea de nuevo la cantidad a elegir para que comience en 1
                cantidad.value = 1;
                let compraModal = bootstrap.Modal.getInstance(document.getElementById("modalCompra"));
                compraModal.hide();
                let finCompraModal = new bootstrap.Modal(document.getElementById("modalFinCompra"));
                finCompraModal.show();
                // Vuelvo a evaluar el stock para completar el recuadro de "Stock en la web"
                completarRecuadroStock()
            }
        });
        */

        // ESTA FUNCION HAY QUE MODIFICARLE LA LOGICA PARA QUE AGREGUE EL PRODUCTO QUE MUESTRA LA PAG AL CARRITO
        // Agregar al carrito
        document.getElementById("btnCarrito").addEventListener("click", agregarAlCarrito);

        // COMIENZO DE VIEJA FUNCION AGREGAR AL CARRITO
        function agregarAlCarrito() {
            let carritoModal = new bootstrap.Modal(document.getElementById("modalCarrito"));
            let carrito = document.getElementById("modalCarrito")
            carrito.querySelector(".modal-body").innerHTML = "Se ha añadido al carrito exitosamente: <br>" + producto.brand_name + " " + producto.model_name
            carrito.querySelector("div.cantidadCarrito").textContent = "Cantidad: " + cantidad.value
            carritoModal.show();
            //HASTA ACÁ FUNCIONA OK

            /* VIEJO CODIGO --------------------------------------------------------------------
            // Almaceno la cantidad que seleccionó a una clave "cantidad:"
            localStorage.setItem('cantidad', cantidad.value);
            

            // Obtener el carrito de localStorage
            let productoACarrito = JSON.parse(localStorage.getItem('carrito')) || [];

            // Crear un objeto que incluya el producto y la cantidad
            const productoConCantidad = {
                ...producto, // Copia todas las propiedades del objeto producto
                cantidad: parseInt(cantidad.value) // Agrega la cantidad seleccionada
            };

            // Agregar el producto con la cantidad al carrito
            productoACarrito.push(productoConCantidad);

            // Guardar el carrito en localStorage
            localStorage.setItem('carrito', JSON.stringify(productoACarrito));
            VIEJO CODIGO ---------------------------------------------------------------------
            */


            // NUEVO CÓDIGO DE CARRITO DONDE SOLAMENTE ALMACENO EL ID Y LA CANTIDAD SELECCIONADA
            // Obtener el carrito de localStorage o iniciar un carrito vacío
            let carritoLocalStorage = JSON.parse(localStorage.getItem('carrito')) || [];

            // Verificar si el producto ya existe en el carrito para actualizar la cantidad
            const productoExistente = carritoLocalStorage.find(item => item.id === producto.id_producto);

            if (productoExistente) {
                // Si el producto ya está en el carrito
                productoExistente.cantidad = parseInt(cantidad.value);
            } else {
                // Si el producto no está en el carrito
                const productoConCantidad = {
                    id: producto.id_producto, // ID del producto
                    cantidad: parseInt(cantidad.value) // Cantidad 
                };
                carritoLocalStorage.push(productoConCantidad);
            }

            // Guarda el carrito actualizado en localStorage
            localStorage.setItem('carrito', JSON.stringify(carritoLocalStorage));

            console.log("Carrito actualizado:");
            carritoLocalStorage.forEach(item => {
                console.log(`ID: ${item.id}, Cantidad: ${item.cantidad}`);
            });

        }

    } else {
        console.error("ID del producto no encontrado.");
    }
}

function completarDatosPagina() {

    document.getElementById("producto-id").textContent = "Código de identificación de producto: " + producto.id_producto;
    document.getElementById("producto-brand-model").textContent = producto.brand_name + " " + producto.model_name;
    document.getElementById("producto-category").textContent = "Categoría: " + producto.category_name;
    actualizarPrecioARS();//Esta función se encarga de calcular y completar el $ARS de acuerdo al precio del "dolar blue" actual
    document.getElementById("producto-image1").src = "/LabWebFrontEnd/" + producto.imagenes[0];
    document.getElementById("producto-image2").src = "/LabWebFrontEnd/" + producto.imagenes[1];
    document.getElementById("producto-description").textContent = producto.descripcion;
}

function completarRecuadroStock() {
    let divStock = document.getElementById("divStock");
    if (producto.stock >= 10) {
        document.querySelector("#divStock h2").innerHTML = "ALTO"
        divStock.style.backgroundColor = "#7ac94c"
    }
    if (producto.stock < 10) {
        document.querySelector("#divStock h2").innerHTML = "BAJO"
        divStock.style.backgroundColor = "#e72c2c"
    }
    if (producto.stock == 0 || producto.habilitado == 0) {
        document.querySelector("#divStock h2").innerHTML = "SIN STOCK"
        divStock.style.backgroundColor = "#e72c2c"
        document.getElementById("btnCompra").disabled = true
        document.getElementById("btnCarrito").disabled = true
    }
}
/* ESTA FUNCION AHORA SE DEBE APLICAR A LA PAGINA DE COMPRA
function realizarCompra() {
    if (producto.stock > 0) {
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
        let compraModal = new bootstrap.Modal(document.getElementById("modalCompra"));
        compraModal.show();
    }
    else {
        // Deshabilitar los botones de compra y carrito si no hay stock
        document.querySelector("#divStock h2").innerHTML = "SIN STOCK"
        divStock.style.backgroundColor = "#e72c2c"
        document.getElementById("btnCompra").disabled = true
        document.getElementById("btnCarrito").disabled = true
    }
}

ESTA FUNCION TAMBIÉN DEBE APLICARSE AHORA A LA PÁGINA DE COMPRA
function validarDatos() {
    let errores = 0;
    let retiroLocal = document.getElementById("retiroLocal").checked;
    if (!retiroLocal) {
        //VALIDAR LARGO DEL NOMBRE
        let nombre = document.getElementById("envioTitular").value
        if (nombre.length < 3 || nombre.length > 50) {
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
        if (calle.length < 3 || calle.length > 30) {
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
        if (isNaN(cp) || cp.length != 4) {
            document.getElementById("envioPostal").classList.add('is-invalid');
            errores += 1
        }
        //VALIDAR NOTAS
        let nota = document.getElementById("envioNota").value
        if (nota.length > 200) {
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
    let tarjeta = document.getElementById("tarjeta").value
    if (tarjeta.length != 12 || isNaN(tarjeta)) {
        document.getElementById("tarjeta").classList.add('is-invalid');
        errores += 1
    }

    console.log("Errores capturados: " + errores)
    if (errores > 0) {
        return false
    } else {
        return true
    }
}
*/


