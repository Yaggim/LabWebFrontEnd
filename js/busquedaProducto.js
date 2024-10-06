document.addEventListener('DOMContentLoaded', function () {
    fetch('/LabWebFrontEnd/ejemploProductos/productos.json')
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
            };
            
            function agregarEventoBusqueda() {
                const buscadores = document.querySelectorAll('.search-bar');
                buscador = seleccionarElementoVisible(buscadores);

                if (buscador) {
                    buscador.addEventListener('keyup', capturarBusqueda);
                }
            };

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
            };

            function generarResultados(productosCoincidentes) {
                if (productosCoincidentes.length > 0) {
                    productosCoincidentes.forEach(producto => {
                        const div = document.createElement('div');
                        div.classList.add('d-flex', 'align-items-center', 'bg-light', 'text-dark', 'p-3');

                        const img = document.createElement('img');
                        
                        img.src = "/LabWebFrontEnd/" + producto.image[0];
                        img.alt = producto.brand.name + ' ' + producto.model.name;
                        img.classList.add('mr-3');
                        img.style.height = '20px'; 

                        const span = document.createElement('span');
                        span.textContent = producto.brand.name + ' ' + producto.model.name;

                        const nombreMarcaModelo = span.textContent;
                        const productNombre = nombreMarcaModelo.trim().toLowerCase().replaceAll(' ', '-');

                        div.appendChild(img);
                        div.appendChild(span);

                        // Agregar un evento de clic al div
                        div.addEventListener('click', function () {
                            // Redirigir a la página del producto
                            window.location.href = `/LabWebFrontEnd/${producto.category.name.toLowerCase()}/${productNombre}/${producto.id}`;
                        });

                        resultadosBusqueda.appendChild(div);
                    });
                }
            };
            
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

                        window.location.href = /LabWebFrontEnd/ + 'resultado-busqueda?query=' + encodeURIComponent(valorBusqueda);
                    });
                }
            };

            function obtenerQueryParametro() {
                const params = new URLSearchParams(window.location.search);
                return params.get('query');
            };
            
            const query = obtenerQueryParametro();
            
            if (query) {
                renderizarProductos(query);
            };

            function renderizarProductos(valorBusqueda) {
                const results = productos.filter(producto =>
                    producto.brand.name.toLowerCase().includes(valorBusqueda.toLowerCase()) ||
                    producto.category.name.toLowerCase().includes(valorBusqueda.toLowerCase()) ||
                    producto.model.name.toLowerCase().includes(valorBusqueda.toLowerCase())
                );
            
                const productContainer = document.getElementById('product-container-search');
                if (results.length === 0) {
                    productContainer.innerHTML = '<p>No se encontraron productos.</p>';
                    productContainer.className = 'alert alert-danger';
                } else {
                    productContainer.innerHTML = '';
                    results.forEach(product => {
                        const productCard = document.createElement('div');
                        productCard.className = 'col-md-4 mb-4';
                        const descripcionTruncada = product.description.length > 100 ? product.description.substring(0, 100) + '...' : product.description;

                        const nombreMarcaModelo = product.brand.name + ' ' + product.model.name;
                        const productNombre = nombreMarcaModelo.trim().toLowerCase().replaceAll(' ', '-');

                        document.getElementById("titulo-categoria").textContent = product.category.name[0].toUpperCase() + product.category.name.substring(1);

                        document.getElementById("title-tag").textContent = 'HardTech - ' + product.category.name[0].toUpperCase() + product.category.name.substring(1);

                        productCard.innerHTML = `
                        <div class="card">
                        <img src="${product.image[0]}" alt="${product.model.name}" style="object-fit: contain; width: 100%; height: 200px;">
                        <div class="card-body">
                        <h5 class="card-title">${nombreMarcaModelo}</h5>
                        <p class="card-text">${descripcionTruncada}</p>
                        <p class="card-text">Precio: $${product.priceARS} / $${product.priceUSD} USD</p>
                        <p class="card-text">Stock: ${product.stock}</p>
                        <a href="${product.category.name.toLowerCase()}/${productNombre}/${product.id}" class="btn btn-primary">Seleccionar</a>
                        </div>
                        </div>
                        `;
                        productContainer.appendChild(productCard);
                    });
                }
            };
            

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