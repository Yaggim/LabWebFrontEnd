$(document).ready(function() {
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
        const carritoDiv = $('#carrito');
        carritoDiv.empty();

        if (Object.keys(carrito).length === 0) {
            carritoDiv.append('<p>El carrito está vacío.</p>');
        } else {
            const table = $('<table class="table"></table>');
            const thead = $('<thead><tr><th>Imagen</th><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead>');
            const tbody = $('<tbody></tbody>');

            for (const productoId in carrito) {
                const item = carrito[productoId];
                const row = $('<tr></tr>');
                row.append(`<td><img src="${item.imagen}" alt="${item.nombre}" width="50"></td>`);
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

    $(document).on('click', '.eliminar', function() {
        const productoId = $(this).data('id');
        const username = 'admin'; // Reemplaza con el nombre de usuario real
        const password = 'password'; // Reemplaza con la contraseña real
        $.ajax({
            url: '../../includes/Admin/eliminar_del_carrito.php',
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
            error: function(error) {
                console.error('Error al eliminar el producto del carrito:', error);
            }
        });
    });

    // Manejar el botón de finalizar compra
    $('#finalizar-compra').on('click', function() {
        // Aquí puedes agregar la lógica para finalizar la compra
        // Por ejemplo, enviar una solicitud AJAX para procesar la compra
        alert('Compra finalizada');
    });

    // Manejar el botón de cancelar compra
    $('#cancelar-compra').on('click', function() {
        // Aquí puedes agregar la lógica para cancelar la compra
        // Por ejemplo, vaciar el carrito
        alert('Compra cancelada');
    });
});