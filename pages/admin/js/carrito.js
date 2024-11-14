$(document).ready(function() {
    // Cargar el carrito desde el backend
    function cargarCarrito() {
        $.ajax({
            url: '../includes/obtener_carrito.php',
            method: 'GET',
            success: function(data) {
                const carrito = JSON.parse(data);
                obtenerDetallesProductos(carrito);
            }
        });
    }

    // Obtener los detalles de los productos desde el backend
    function obtenerDetallesProductos(carrito) {
        $.ajax({
            url: '../includes/obtener_productos.php',
            method: 'GET',
            success: function(data) {
                const productos = JSON.parse(data);
                // Añadir detalles de los productos al carrito
                for (const productoId in carrito) {
                    if (productos[productoId]) {
                        carrito[productoId].nombre = productos[productoId].nombre;
                        carrito[productoId].precio = productos[productoId].precio;
                    }
                }
                renderizarCarrito(carrito);
            }
        });
    }

    // Renderizar el carrito en el DOM
    function renderizarCarrito(carrito) {
        const carritoDiv = $('#carrito');
        carritoDiv.empty();

        if (Object.keys(carrito).length === 0) {
            carritoDiv.append('<p>El carrito está vacío.</p>');
        } else {
            const table = $('<table class="table"></table>');
            const thead = $('<thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead>');
            const tbody = $('<tbody></tbody>');

            for (const productoId in carrito) {
                const item = carrito[productoId];
                const row = $('<tr></tr>');
                row.append(`<td>${item.nombre}</td>`);
                row.append(`<td>${item.precio}</td>`);
                row.append(`<td>${item.cantidad}</td>`);
                row.append(`<td>${item.precio * item.cantidad}</td>`);
                row.append(`<td><button class="btn btn-danger eliminar" data-id="${productoId}">Eliminar</button></td>`);
                tbody.append(row);
            }

            table.append(thead);
            table.append(tbody);
            carritoDiv.append(table);
        }
    }

    // Eliminar producto del carrito
    $(document).on('click', '.eliminar', function() {
        const productoId = $(this).data('id');
        $.ajax({
            url: '../includes/eliminar_del_carrito.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ producto_id: productoId }),
            success: function(data) {
                const response = JSON.parse(data);
                if (response.success) {
                    cargarCarrito();
                } else {
                    console.error('Error al eliminar el producto del carrito:', response.message);
                }
            }
        });
    });

    // Inicializar el carrito al cargar la página
    cargarCarrito();
});