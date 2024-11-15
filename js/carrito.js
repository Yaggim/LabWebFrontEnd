/* var productos = [];

async function fetchProducts(id = null) {
    const url = `../../includes/admin/producto.php?id_producto=${id}`;
    const response = await fetch(url);
    return response.json();
}

async function cargarCarrito() {
    try {
        // Limpiar el array de productos antes de cargar nuevos datos
        productos = [];

        // Obtener el carrito de localStorage
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        // Iterar sobre cada item en el carrito y cargar sus datos desde la base de datos
        for (const item of carrito) {
            const producto = await fetchProducts(item.id_producto); // Obtener datos del producto por ID
            producto.cantidad = item.cantidad; // Agregar la cantidad seleccionada del carrito
            productos.push(producto); // Agregar el producto completo al array productos
        }

        // Mostrar los productos cargados en la consola para verificar
        console.log("Productos en el carrito:", productos);
    } catch (error) {
        console.error("Error cargando el carrito de productos:", error);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    
    let carritoDiv = document.getElementById('carrito');

    // Carrito de localStorage
    let carrito = cargarCarrito();

    // Botones de eliminar y finalizar compra
    let eliminarButtons = document.getElementsByClassName('eliminar');
    let finalizarCompraButton = document.getElementById('finalizar-compra');
    let cancelarButton = document.getElementById('cancelar-compra');

    // Oculta los botones de eliminar y finalizar compra inicialmente
    for (let button of eliminarButtons) {
        button.style.display = 'none';
    }
    finalizarCompraButton.style.display = 'none';

    // Para cada producto en el carrito
    for (let producto of carrito) {
        console.log("Producto ID: " + producto.id_producto);
        // Crea un nuevo div para el producto
        let productoDiv = document.createElement('div');
        productoDiv.className = 'producto';

        // Comprueba que producto.brand y producto.model existen antes de intentar acceder a sus propiedades
        let brandName = producto.brand ? producto.brand.name : 'Marca desconocida';
        let modelName = producto.model ? producto.model.name : 'Modelo desconocido';

        // Establece el contenido del div del producto
        let srcImageOriginal = producto.image[0];
        let srcImageCarrito = srcImageOriginal.replace("../", "");

        productoDiv.innerHTML = `<img src="${srcImageCarrito}" alt="${brandName} ${modelName}" class="imagenProducto"> 
                <div>${brandName} ${modelName} | Cantidad: ${producto.cantidad || 1}  
                | Precio unitario: $${producto.priceARS} </div>  `;

        // Agrega el div del producto al div del carrito
        carritoDiv.appendChild(productoDiv);

        // Si hay un producto en el carrito, agrega el botón de eliminar
        if (carrito.length > 0) {
            console.log("Contenido del carrito:", carrito);
            // Establece el contenido del div del producto
            productoDiv.innerHTML = `<img src="${srcImageCarrito}" alt="${brandName} ${modelName}" class="imagenProducto"> 
                            <div>${brandName} ${modelName} | Cantidad: ${producto.cantidad || 1}  
                            | Precio unitario: $${producto.priceARS} </div>  `;
            // Agrega el div del producto al div del carrito
            carritoDiv.appendChild(productoDiv);
            let eliminarButton = document.createElement('button');
            eliminarButton.className = 'eliminar mx-3';
            eliminarButton.id = 'eliminar-' + producto.id;
            eliminarButton.textContent = 'Eliminar';
            productoDiv.appendChild(eliminarButton);

            eliminarButton.addEventListener('click', function () {
                eliminarElementoDelCarrito(producto.id);
            });
        }
    }

    let totalDiv = document.createElement('div');
    totalDiv.className = 'total';
    totalDiv.id = 'total';

    let total = 0;
    for (let producto of carrito) {
        total += producto.priceARS * (producto.cantidad || 1);
    }

    totalDiv.textContent = `Total: $${total}`;

    carritoDiv.appendChild(totalDiv);

    // Si hay productos en el carrito, muestra el botón de finalizar compra
    if (carrito.length > 0) {
        finalizarCompraButton.style.display = 'block';
    }

    // Función para eliminar un elemento del carrito
    function eliminarElementoDelCarrito(id) {
        // Obtén el carrito de localStorage
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        // Encuentra el índice del producto con el id dado en el carrito
        let index = carrito.findIndex(producto => producto.id === id);

        // Si el producto se encuentra en el carrito, elimínalo
        if (index !== -1) {
            carrito.splice(index, 1);
        }

        // Guarda el carrito actualizado en localStorage
        localStorage.setItem('carrito', JSON.stringify(carrito));

        // Recarga la página para actualizar la lista de productos en el carrito
        location.reload();
    }

    // Si no hay más productos en el carrito, oculta el botón de finalizar compra
    if (carrito.length === 0) {
        finalizarCompraButton.style.display = 'none';
        carritoDiv.style.alignItems = 'center';
        carritoDiv.innerHTML = `<p>No hay elementos agregados al carrito</p>`;
    }
    //  *************** INICIO CODIGO VIEJO  ***************
        // Función para finalizar la compra
        function finalizarCompra() {
            window.location.href = 'finalizar-compra';
        }
     //*************** FIN CODIGO VIEJO  ***************
    /*

    // *************** INICIO NUEVO CODIGO  ***************
    // Función para finalizar la compra
    function finalizarCompra() {
        // Obtén el carrito de localStorage
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        // Envía el carrito al servidor usando fetch con método POST
        fetch('views/compra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ carrito: carrito }) // Enviamos el carrito como JSON
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // CONFIRMAR COMPRA
                    window.location.href = 'confirmacion-compra'; // 
                } else {
                    // Manejar errores
                    console.error('Error en la compra:', data.message);
                }
            })
            .catch(error => console.error('Error en la solicitud:', error));
            window.location.href = 'finalizar-compra';
    }
    */
    // *************** FIN NUEVO CODIGO  ***************
    

   /* finalizarCompraButton.addEventListener('click', finalizarCompra);

    // Cancelar regresa a la página anterior
    cancelarButton.addEventListener('click', function () {
        window.history.back();
    });
}); */

document.addEventListener('DOMContentLoaded', function() {
    function verificarUsuarioLogueado() {
        return $.ajax({
            url: '../../includes/usuario.php',
            method: 'GET',
            success: function(data) {
                const response = JSON.parse(data);
                return response.logueado;
            },
            error: function(error) {
                console.error('Error al verificar el estado del usuario:', error);
                return false;
            }
        });
    }

    function cargarCarrito() {
        const username = "admin";
        const password = "admin";
        $.ajax({
            url: '../../includes/Admin/obtener_carrito.php',
            method: 'GET',
            data: JSON.stringify({ username: username, password: password }),
            success: function(data) {
                const carrito = JSON.parse(data);
                renderizarCarrito(carrito);
            },
            error: function(error) {
                console.error('Error al cargar el carrito:', error);
            }
        });
    }

    function renderizarCarrito(carrito) {
        const carritoDiv = document.getElementById('carrito');
        carritoDiv.innerHTML = '';

        const finalizarCompraBtn = document.getElementById('finalizar-compra');
        if (Object.keys(carrito).length === 0) {
            carritoDiv.innerHTML = '<p>El carrito está vacío.</p>';
            finalizarCompraBtn.style.display = 'none';
        } else {
            finalizarCompraBtn.style.display = 'block';
            const table = document.createElement('table');
            table.classList.add('table');
            const thead = document.createElement('thead');
            thead.innerHTML = '<tr><th>Imagen</th><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr>';
            const tbody = document.createElement('tbody');

            for (const productoId in carrito) {
                const item = carrito[productoId];
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><img src="${item.imagen}" alt="${item.nombre}" width="50"></td>
                    <td>${item.nombre}</td>
                    <td>${item.precio}</td>
                    <td>${item.cantidad}</td>
                    <td>${item.precio * item.cantidad}</td>
                    <td><button class="btn btn-danger eliminar" data-id="${productoId}">Eliminar</button></td>
                `;
                tbody.appendChild(row);
            }

            table.appendChild(thead);
            table.appendChild(tbody);
            carritoDiv.appendChild(table);
        }
    }

   // Añadir event listeners a los botones de eliminación
   const eliminarBtns = document.querySelectorAll('.eliminar');
   eliminarBtns.forEach(function(btn) {
       btn.addEventListener('click', function() {
           const productoId = this.getAttribute('data-id');
           const username = 'admin'; // Reemplaza con el nombre de usuario real
           const password = 'password'; // Reemplaza con la contraseña real
           $.ajax({
               url: '/mi-proyecto/eliminar_del_carrito.php',
               method: 'POST',
               contentType: 'application/json',
               data: JSON.stringify({ producto_id: productoId, username: username, password: password }),
               success: function(data) {
                   const response = JSON.parse(data);
                   if (response.success) {
                       cargarCarrito();
                   } else {
                       console.error('Error al eliminar el producto del carrito:', response.message);
                   }
               },
               error: function(xhr, status, error) {
                   console.error('Error al eliminar el producto del carrito:', error);
               }
           });
       });
   });


    verificarUsuarioLogueado().then(function(logueado) {
        if (logueado) {
            cargarCarrito();
        } else {
            document.getElementById('carrito').innerHTML = '<p>Debe iniciar sesión para realizar compras.</p>';
        }
    });

    // Manejar el botón de finalizar compra
    document.getElementById('finalizar-compra').addEventListener('click', function() {
        // Aquí puedes agregar la lógica para finalizar la compra
        // Por ejemplo, enviar una solicitud AJAX para procesar la compra
        alert('Compra finalizada');
    });

    // Manejar el botón de cancelar compra
    document.getElementById('cancelar-compra').addEventListener('click', function() {
        // Aquí puedes agregar la lógica para cancelar la compra
        // Por ejemplo, vaciar el carrito
        alert('Compra cancelada');
    });
});