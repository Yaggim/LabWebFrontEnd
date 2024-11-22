document.addEventListener('DOMContentLoaded', function() {
    let resultadosBusqueda, botonBusqueda, buscador;
    const basePath = window.location.pathname.split('/')[1];

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

    function agregarEventoBotonBusqueda() {
        const botonesBusqueda = document.querySelectorAll('.btn-custom-search');
        botonBusqueda = seleccionarElementoVisible(botonesBusqueda);

        if (botonBusqueda) {
            botonBusqueda.addEventListener('click', function(event) {
                event.preventDefault();
                capturarBusqueda.call(buscador);
            });
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

        fetch(`/${basePath}/includes/Admin/busquedaProducto.php?termino=${encodeURIComponent(valorBusqueda)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            console.log('Response status:', response.status); 
            console.log('Response status text:', response.statusText); 
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            generarResultados(data);
        })
        .catch(error => {
            console.error('Error al realizar la búsqueda:', error);
        });
    }

    function generarResultados(productosCoincidentes) {
        resultadosBusqueda.innerHTML = '';
        if (productosCoincidentes.length > 0) {
            productosCoincidentes.forEach(producto => {
                console.log('Producto ID:', producto.id_producto); 
                const div = document.createElement('div');
                div.classList.add('d-flex', 'align-items-center', 'bg-light', 'text-dark', 'p-3');

                const img = document.createElement('img');
                img.src = `/${basePath}/${producto.imagen}`;
                img.alt = producto.marca + ' ' + producto.modelo;
                img.classList.add('mr-3');
                img.style.height = '20px';

                const span = document.createElement('span');
                span.textContent = producto.marca + ' ' + producto.modelo;

                div.appendChild(img);
                div.appendChild(span);

                div.addEventListener('click', function() {

                    const procesarNombre = (nombre) => nombre.toLowerCase().replace(/\s+/g, '-').normalize('NFD').replace(/[\u0300-\u036f]/g, '');

                    const categoria = procesarNombre(producto.categoria);
                    const marca = procesarNombre(producto.marca);
                    const modelo = procesarNombre(producto.modelo);
                    const url = `/${basePath}/${categoria}/${marca}-${modelo}/${producto.id_producto}`;
                    console.log('Generated URL:', url);
                    window.location.href = url;
                });

                resultadosBusqueda.appendChild(div);
            });
        } else {
            resultadosBusqueda.innerHTML = '<li>No se encontraron productos.</li>';
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
});