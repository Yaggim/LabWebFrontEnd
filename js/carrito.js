document.addEventListener('DOMContentLoaded', function () {
    let carritoDiv = document.getElementById('carrito');

    // Carrito de localStorage
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    console.log(carrito);

    // Botones de eliminar y finalizar compra

    let finalizarCompraButton = document.getElementById('btnCompra');
    let cancelarButton = document.getElementById('cancelar-compra');



    // Para cada elemento en el carrito
    for (let item of carrito) {
        console.log("Elemento ID: " + item.id);
        // Crea un nuevo div para el elemento
        let itemDiv = document.createElement('div');
        itemDiv.className = 'producto';

        // Comprueba si es un combo o un producto normal
        if (item.productos) {
            // Es un combo
            let nombreCombo = item.nombre;
            let srcImageCombo = '../' + item.imagen;

            itemDiv.innerHTML = `<img src="${srcImageCombo}" alt="${nombreCombo}" class="imagenProducto"> 
                <div>${nombreCombo} | Cantidad: ${item.cantidad || 1}  
                | Descuento: ${item.descuento}% | Precio: $${item.precioEnPesos.toFixed(2)}</div>`;
        } else {
            // Es un producto normal
            let brandName = item.marca;
            let modelName = item.modelo;
            let srcImageProducto = '../' + item.imagen;

            itemDiv.innerHTML = `<img src="${srcImageProducto}" alt="${brandName} ${modelName}" class="imagenProducto"> 
                <div>${brandName} ${modelName} | Cantidad: ${item.cantidad || 1}  
                | Precio unitario: $${item.precioEnPesos.toFixed(2)}</div>`;
        }

        // Agrega el div del elemento al div del carrito
        carritoDiv.appendChild(itemDiv);

        // Si hay un elemento en el carrito, agrega el botón de eliminar
        if (carrito.length > 0) {
            console.log("Contenido del carrito:", carrito);
            let eliminarButton = document.createElement('button');
            eliminarButton.className = 'eliminar mx-3';
            eliminarButton.id = 'eliminar-' + item.id;
            eliminarButton.textContent = 'Eliminar';
            itemDiv.appendChild(eliminarButton);

            eliminarButton.addEventListener('click', function () {
                eliminarElementoDelCarrito(item.id);
            });
        }
    }

    let totalDiv = document.createElement('div');
    totalDiv.className = 'total';
    totalDiv.id = 'total';

    let total = 0;
    for (let producto of carrito) {
        total += producto.precioEnPesos * (producto.cantidad || 1);
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

    //agregado ahora
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
            let response = await fetch('guardar_carrito.php', {
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

    /*
    // Función para finalizar la compra
    function finalizarCompra() {
        window.location.href = 'finalizar-compra';
    }

    finalizarCompraButton.addEventListener('submit', finalizarCompra);
    */

    // Cancelar regresa a la página anterior
    cancelarButton.addEventListener('click', function () {
        console.log('Cancelar compra');
        window.history.back();
    });
});