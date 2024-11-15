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
        document.getElementById("producto-priceARS").textContent = "Precio AR$ " + precioEnPesos.toFixed(2) +".-";
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
                const precioEnPesos = parseFloat(producto.precio_usd * dolarBlue);
                const productoConCantidad = {
                    id: producto.id_producto, // ID del producto
                    cantidad: parseInt(cantidad.value), // Cantidad 
                    precioEnPesos: precioEnPesos,
                    marca: producto.brand_name,
                    modelo: producto.model_name,
                    imagen: producto.imagenes[0]
                    

                };
                carritoLocalStorage.push(productoConCantidad);
            }

            // Guarda el carrito actualizado en localStorage
            localStorage.setItem('carrito', JSON.stringify(carritoLocalStorage));

            console.log("Carrito actualizado:");
            carritoLocalStorage.forEach(item => {
                console.log(carritoLocalStorage)
                console.log(`ID: ${item.id}, Cantidad: ${item.cantidad}, Precio Unitario: ${item.precioEnPesos}`);
            });

        }

    } else {
        console.error("ID del producto no encontrado.");
    }
}

function enviarCarritoAlServidor() {
    let carritoLocalStorage = JSON.parse(localStorage.getItem('carrito')) || [];

    fetch('../../views/compra.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ carrito: carritoLocalStorage })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
    })
    .catch(error => {
        console.error('Error al enviar el carrito:', error);
    });
}


function completarDatosPagina() {
    const basePath = window.location.pathname.split('/')[1];

    document.getElementById("producto-id").textContent = "Código de identificación de producto: " + producto.id_producto;
    document.getElementById("producto-brand-model").textContent = producto.brand_name + " " + producto.model_name;
    document.getElementById("producto-category").textContent = "Categoría: " + producto.category_name;
    actualizarPrecioARS();//Esta función se encarga de calcular y completar el $ARS de acuerdo al precio del "dolar blue" actual
    document.getElementById("producto-image1").src =  `/${basePath}/${producto.imagenes[0]}`;
    document.getElementById("producto-image2").src = `/${basePath}/${producto.imagenes[1]}`;
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
/*
document.getElementById('btnCompra').addEventListener('click', function (event) {
    event.preventDefault();

    // Obtener el carrito del localStorage
    let carritoLocalStorage = JSON.parse(localStorage.getItem('carrito')) || [];

    // Asegurar que el carrito no esté vacío antes de enviar
    if (carritoLocalStorage.length === 0) {
        alert('El carrito está vacío. Agrega productos antes de realizar la compra.');
        return;
    }

    // Rellenar el campo oculto del formulario
    let carritoInput = document.getElementById('carritoInput');
    carritoInput.value = JSON.stringify(carritoLocalStorage);

    // Confirmar en consola lo que se enviará
    console.log('Carrito enviado:', carritoInput.value);

    // Enviar el formulario
    document.getElementById('comprar').submit();})
    */
    document.getElementById('btnCompra').addEventListener('click', async function (event) {
        event.preventDefault(); 
    
        // Obtener el carrito
        let carritoLocalStorage = JSON.parse(localStorage.getItem('carrito')) || [];
    
        // Asegurarnos de que el carrito no esté vacío
        if (carritoLocalStorage.length === 0) {
            alert('El carrito está vacío. Agrega productos antes de realizar la compra.');
            // EN ESTE CASO HAY QUE ALMACENAR TODOS LOS DATOS DEL PRODCUTO QUE SE ESTÁ MOSTRANDO POR PANTALLA
            // AL CARRITO Y MANDARLO, BÁSICAMENTE ES UN CARRITO DE 1 PRODUCTO ESE CASO
            return;
        }
    
        // Enviar el carrito al servidor para guardarlo en la sesión
        try {
            let response = await fetch('../../views/guardar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ carrito: carritoLocalStorage }),
            });
    
            if (response.ok) {
                document.getElementById("comprar").submit();
            } 
        } catch (error) {
            console.error('Error al enviar el carrito:', error);
        }
    });
