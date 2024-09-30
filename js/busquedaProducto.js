document.addEventListener('DOMContentLoaded', function () {
    fetch('../ejemploProductos/productos.json')
        .then(response => response.json())
        .then(data => {
            const productos = data;
            let resultadosBusqueda,
                botonBusqueda,
                buscador;

            function seleccionarElementoVisible(elementos) {
                let elementoVisible = null;
                elementos.forEach(function(elemento) {
                    if (elemento.offsetParent !== null) {
                        elementoVisible = elemento;
                    }
                });
                return elementoVisible;
            }
            
            function agregarEventoBusqueda() {
                const buscadores = document.querySelectorAll('.search-bar');
                buscador = seleccionarElementoVisible(buscadores);

                if (buscador) {
                    buscador.addEventListener('keyup', capturarBusqueda);
                }
            }

            function capturarBusqueda() {
                const valorBusqueda = this.value.toLowerCase();
                const resultadosBusquedas = document.querySelectorAll('.resultados-busqueda');

                resultadosBusqueda = seleccionarElementoVisible(resultadosBusquedas);
                resultadosBusqueda.innerHTML = '';

                if (valorBusqueda.trim() === '') {
                    return;
                }

                const productosCoincidentes = productos.filter(producto => {
                    return producto.brand.name.toLowerCase().includes(valorBusqueda) ||
                        producto.model.name.toLowerCase().includes(valorBusqueda) ||
                        producto.category.name.toLowerCase().includes(valorBusqueda);
                });

                generarResultados(productosCoincidentes);
            }

            function generarResultados(productosCoincidentes) {
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
            }
            
            function agregarEventoBotonBusqueda() {
                const botonesBusqueda = document.querySelectorAll('.btn-custom-search');
                botonBusqueda = seleccionarElementoVisible(botonesBusqueda);

                if (botonBusqueda) {
                    botonBusqueda.addEventListener('click', function (event) {
                        event.preventDefault();

                        const valorBusqueda = buscador.value.toLowerCase();

                        if (valorBusqueda.trim() === '') {
                            return;
                        }

                        window.location.href = '../views/producto.html?query=' + encodeURIComponent(valorBusqueda);
                    });
                }
            }

            const modal = document.getElementById('searchModal');
            modal.addEventListener('shown.bs.modal', function () {
                agregarEventoBusqueda();
                agregarEventoBotonBusqueda();
            });

            modal.addEventListener('hidden.bs.modal', function () {
                agregarEventoBotonBusqueda();
            });
            
            agregarEventoBusqueda();
            agregarEventoBotonBusqueda();
        })
        
        .catch(error => console.error('Error:', error));
});