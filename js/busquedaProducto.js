document.addEventListener('DOMContentLoaded', function() {
    let resultadosBusqueda, botonBusqueda, buscador;

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
        const botonesBusqueda = document.querySelectorAll('.boton-Busqueda');
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

        fetch(`/LabWebFrontEnd/includes/Admin/busquedaProducto.php?termino=${encodeURIComponent(valorBusqueda)}`, {
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
                img.src = `/LabWebFrontEnd/${producto.imagen}`;
                img.alt = producto.marca + ' ' + producto.modelo;
                img.classList.add('mr-3');
                img.style.height = '20px';

                const span = document.createElement('span');
                span.textContent = producto.marca + ' ' + producto.modelo;

                div.appendChild(img);
                div.appendChild(span);

                div.addEventListener('click', function() {
                     const categoria = producto.categoria.toLowerCase().replace(/\s+/g, '-');
                     const marca = producto.marca.toLowerCase().replace(/\s+/g, '-');
                     const modelo = producto.modelo.toLowerCase().replace(/\s+/g, '-');
                     const url = `/LabWebFrontEnd/${categoria}/${marca}-${modelo}/${producto.id_producto}`;
                     console.log('Generated URL:', url);
                     window.location.href = url;
                });

                resultadosBusqueda.appendChild(div);
            });
        } else {
            resultadosBusqueda.innerHTML = '<li>No se encontraron productos.</li>';
        }
    }

    agregarEventoBusqueda();
    agregarEventoBotonBusqueda();
});