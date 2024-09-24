const productos = [
    {
        "id": 1,
        "brand": {
            "id": 1,
            "name": "Kingston"
        },
        "model": {
            "id": 1,
            "name": "HyperX Fury DDR4"
        },
        "category": {
            "id": 1,
            "name": "Memorias"
        },
        "priceARS": 1000,
        "priceUSD": 10,
        "enabled": true,
        "stock": 10,
        "image": [
            "../images/memoria1.jpg",
            "../images/memoria2.jpg",
            "../images/memoria3.jpg"
        ],
        "description": "Si tu computadora funciona con lentitud, si un programa no responde o no se carga, lo más probable es que se trate de un problema de memoria. Estos son posibles indicios de un rendimiento defectuoso en el día a día de tus tareas. Por ello, contar con una memoria Kingston -sinónimo de trayectoria y excelencia- mejorará la productividad de tu equipo: las páginas se cargarán más rápido y la ejecución de nuevas aplicaciones resultará más ágil y simple."
    }
];

// Llamada a la función principal con el ID del producto
cargarProductoPorID(1);

function cargarProductoPorID(idProducto) {
    // Buscar el producto por el ID
    const producto = productos.find(prod => prod.id === idProducto);

    // Cargar los datos en la página 
    if (producto) {
        document.getElementById("producto-id").textContent = "Código de identificación de producto: " + producto.id;
        document.getElementById("producto-brand-model").textContent = producto.brand.name + " " + producto.model.name;
        document.getElementById("producto-category").textContent = "Categoría: " + producto.category.name;
        document.getElementById("producto-priceARS").textContent = "Precio: $" + producto.priceARS; //+ " / USD: $" + producto.priceUSD;
        document.getElementById("producto-image1").src = producto.image[0];
        document.getElementById("producto-image2").src = producto.image[1];
        document.getElementById("producto-image3").src = producto.image[2];
        document.getElementById("producto-description").textContent = producto.description;
        let divStock = document.getElementById("divStock");
        if (producto.stock < 10) {
            document.querySelector("#divStock h2").innerHTML = "BAJO"
            divStock.style.backgroundColor = "#e72c2c"
        }
        if (producto.stock >= 10) {
            document.querySelector("#divStock h2").innerHTML = "ALTO"
            divStock.style.backgroundColor = "#7ac94c"
        }

        // Incremento y decremento de cantidad seleccionada del producto

        const decrement = document.getElementById('btnDecrement');
        const increment = document.getElementById('btnIncrement');
        const cantidad = document.getElementById('quantity');
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
        // INCOMPLETO
        document.getElementById("btnCompra").addEventListener("click", realizarCompra);
        function realizarCompra(idProducto) {
            if (producto.stock > 0) {
                alert("(EL PRODUCTO CUENTA CON DISPONIBILIDAD) -> DIRIGE A PASARELA DE PAGO")
            }

        }

        // Formulario de datos del modal envío a domicilio
        document.getElementById("formEnvio").addEventListener("submit", function (event) {
            event.preventDefault();
            let calle = document.getElementById("envioCalle").value;
            let altura = document.getElementById("envioAltura").value;
            let postal = document.getElementById("envioPostal").value;
            let nota = document.getElementById("envioNota").value;
            console.log("Calle:", calle, "Altura:", altura, "Código Postal:", postal, "Nota:", nota);
            alert("Envío cargado satisfactoriamente!");
            let envioModal = bootstrap.Modal.getInstance(document.getElementById("modalEnvio"));
            envioModal.hide();
        });

        // Mostrar modal de envío a domicilio
        document.getElementById("btnEnvio").addEventListener("click", envioDomicilio);
        function envioDomicilio() {
            let envioModal = new bootstrap.Modal(document.getElementById("modalEnvio"));
            envioModal.show();
        }

        // Agregar al carrito
        document.getElementById("btnCarrito").addEventListener("click", agregarAlCarrito);
        function agregarAlCarrito() {
            let carritoModal = new bootstrap.Modal(document.getElementById("modalCarrito"));
            let carrito = document.getElementById("modalCarrito")
            carrito.querySelector(".modal-body").innerHTML = "Se ha añadido al carrito exitosamente: <br>" + producto.brand.name + " " + producto.model.name
            carrito.querySelector("div.cantidadCarrito").textContent = "Cantidad: " + cantidad.value
            carritoModal.show();
        }


        // Si no se encuentra el producto con el ID
    } else {
        console.error("Producto no encontrado.");
    }
}

