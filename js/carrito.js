document.addEventListener('DOMContentLoaded', function () {
    let carritoDiv = document.getElementById('carrito');

    // Carrito de localStorage
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

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
        // Crea un nuevo div para el producto
        let productoDiv = document.createElement('div');
        productoDiv.className = 'producto';

        // Comprueba que producto.brand y producto.model existen antes de intentar acceder a sus propiedades
        let brandName = producto.brand ? producto.brand.name : 'Marca desconocida';
        let modelName = producto.model ? producto.model.name : 'Modelo desconocido';

        // Establece el contenido del div del producto
        productoDiv.innerHTML = `
        <img src="${producto.image[0]}" alt="${brandName} ${modelName}" class="imagenProducto"> 
        <div>${brandName} ${modelName} | Cantidad: ${producto.cantidad || 1}  
        | Precio unitario: $${producto.priceARS} </div>  
        `;

        // Agrega el div del producto al div del carrito
        carritoDiv.appendChild(productoDiv);

        // Si hay un producto en el carrito, agrega el botón de eliminar
        if (carrito.length > 0) {
            let eliminarButton = document.createElement('button');
            eliminarButton.className = 'eliminar';
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
        cancelarButton.style.display = 'none';
        carritoDiv.style.alignItems = 'center';
        carritoDiv.innerHTML = `<p>No hay elementos agregados al carrito</p>`;
    }

    // Función para finalizar la compra
    function finalizarCompra() {
        window.location.href = 'compra.html';
    }

    finalizarCompraButton.addEventListener('click', finalizarCompra);

    // Cancelar regresa a la página anterior
    cancelarButton.addEventListener('click', function () {
        window.history.back();
    });
});