document.addEventListener('DOMContentLoaded', function () {
    var carrito = document.getElementById('carrito');

    // Elemento a agregar al carrito. Queda por definir. 
    var elemento

    // Función para agregar un elemento al carrito
    function agregarElementoAlCarrito(elemento) {
        var elementoHTML = '<p>' + elemento.nombre + ' - $' + elemento.precio + '</p>';
        carrito.innerHTML += elementoHTML;

        // Agrego un evento de clic al botón de eliminar
        document.getElementById('elemento-' + elemento.id).getElementsById('eliminar')[0].addEventListener('click', function () {
            eliminarElementoDelCarrito(elemento.id);
        });
    }

    // Función para eliminar un elemento del carrito
    function eliminarElementoDelCarrito(id) {
        var elemento = document.getElementById('elemento-' + id);
        carrito.removeChild(elemento);
    }

    carrito.innerHTML = '<p>No hay elementos seleccionados en este momento.</p>';

    // Agrego un elemento al carrito
    agregarElementoAlCarrito(elemento);

    document.getElementById('finalizar-compra').addEventListener('click', function () {
        finalizarCompra();
    });

    // Función para finalizar la compra
    function finalizarCompra() {
        window.location.href = 'compra.html';  
    }
});
