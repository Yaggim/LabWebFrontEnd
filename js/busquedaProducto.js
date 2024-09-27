document.addEventListener('DOMContentLoaded', function () {
    fetch('../ejemploProductos/productos.json')
        .then(response => response.json())
        .then(data => {
            const productos = data;
            const buscador = document.getElementById('search-bar');
            const resultadosBusqueda = document.getElementById('resultados-busqueda');
            const botonBusqueda = document.querySelector('.btn-custom');  

            buscador.addEventListener('keyup', function () {
                const valorBusqueda = this.value.toLowerCase();

                resultadosBusqueda.innerHTML = '';

                if (valorBusqueda.trim() === '') {
                    return;
                }

                const productosCoincidentes = productos.filter(producto => {
                    return producto.brand.name.toLowerCase().includes(valorBusqueda) ||
                        producto.model.name.toLowerCase().includes(valorBusqueda) ||
                        producto.category.name.toLowerCase().includes(valorBusqueda);
                });

                // Si se encuentran productos, actualizar la interfaz de usuario con los detalles de los productos
                if (productosCoincidentes.length > 0) {
                    productosCoincidentes.forEach(producto => {
                        const div = document.createElement('div');
                        div.classList.add('d-flex', 'align-items-center', 'bg-light', 'text-dark', 'p-3');

                        const img = document.createElement('img');
                        
                        img.src = producto.image[0];
                        img.alt = producto.brand.name + ' ' + producto.model.name;
                        img.classList.add('mr-3');
                        img.style.height = '20px'; 

                        const span = document.createElement('span');
                        span.textContent = producto.brand.name + ' ' + producto.model.name;

                        div.appendChild(img);
                        div.appendChild(span);

                         // Agregar un evento de clic al div
                         div.addEventListener('click', function () {
                            // Redirigir a la página del producto
                            window.location.href = `../views/producto.html?id=${producto.id}`;
                        });

                        resultadosBusqueda.appendChild(div);
                    });
                }
            });

            botonBusqueda.addEventListener('click', function (event) {
                event.preventDefault(); 

                const valorBusqueda = buscador.value.toLowerCase();

                if (valorBusqueda.trim() === '') {
                    return;
                }

                window.location.href = '../views/producto.html?query=' + encodeURIComponent(valorBusqueda);
            });
        })
        .catch(error => console.error('Error:', error));
});