document.addEventListener('DOMContentLoaded', () => {
    const ofertasContainer = document.getElementById('ofertas-container');
    let dolarBlue = 0;

    async function fetchDolarBlue() {
        const response = await fetch("https://dolarapi.com/v1/dolares/blue");
        const data = await response.json();
        dolarBlue = data.venta;
    }

    async function fetchOfertasSemana() {
        const response = await fetch('./includes/admin/ofertaSemana.php');
        return response.json();
    }

    async function loadOfertasSemana() {
        try {
            await fetchDolarBlue();
            const ofertas = await fetchOfertasSemana();
            displayOfertas(ofertas);
        } catch (error) {
            console.error('Error cargando ofertas de la semana:', error);
        }
    }

    function mostrarModalError(mensaje) {
        const modalError = new bootstrap.Modal(document.getElementById('modalError'));
        document.getElementById('modalErrorMensaje').textContent = mensaje;
        modalError.show();
    
        
        document.getElementById('modalErrorCerrar').addEventListener('click', () => {
            modalError.hide();
        });
    }

    function displayOfertas(ofertas) {
        ofertasContainer.innerHTML = '';

        const today = new Date();

        ofertas.forEach(oferta => {
            const fechaInicio = new Date(oferta.fecha_inicio);
            const fechaFin = new Date(oferta.fecha_fin);

            if (today >= fechaInicio && today <= fechaFin) {
                const precioOriginal = oferta.producto_precio_usd * dolarBlue;
                const precioConDescuento = precioOriginal * (1 - oferta.descuento / 100);

                const ofertaCard = document.createElement('div');
                ofertaCard.className = 'col-md-4 mb-4';

                // Asegúrate de que oferta.imagenes sea un array
                const imagenes = typeof oferta.imagenes === 'string' ? oferta.imagenes.split(',') : oferta.imagenes;

                let imagenesHTML = '';
                imagenes.forEach((imagen, index) => {
                    imagenesHTML += `
                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                            <img src="${imagen}" alt="${oferta.producto_modelo}" class="d-block w-100">
                        </div>
                    `;
                });

                ofertaCard.innerHTML = `
                    <div class="card">
                        <div id="carousel${oferta.id_oferta_semana}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                ${imagenesHTML}
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel${oferta.id_oferta_semana}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel${oferta.id_oferta_semana}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${oferta.descripcion}</h5>
                            <p class="card-text">${oferta.producto_descripcion}</p>
                            <p class="card-text">Precio Original: $${precioOriginal.toFixed(2)}</p>
                            <p class="card-text">Precio con Descuento: $${precioConDescuento.toFixed(2)}</p>
                            <p class="card-text">Descuento: ${oferta.descuento}%</p>
                            <p class="card-text">Stock: ${oferta.producto_stock}</p>
                            <input type="number" id="cantidad${oferta.id_producto}" class="form-control mb-2" min="1" max="${oferta.producto_stock}" value="1">
                            <button class="btn btn-primary" onclick="agregarAlCarrito(${oferta.id_producto}, ${precioConDescuento.toFixed(2)}, '${oferta.producto_marca}', '${oferta.producto_modelo}', '${imagenes[0]}', ${oferta.producto_stock})">Agregar al carrito</button>
                        </div>
                    </div>
                `;

                ofertasContainer.appendChild(ofertaCard);
            }
        });
    }

    window.agregarAlCarrito = function(id, precioEnPesos, marca, modelo, imagen, stock) {

        const cantidadInput = document.getElementById(`cantidad${id}`);
        const cantidad = parseInt(cantidadInput.value);

        if (cantidad > stock) {
            mostrarModalError(`La cantidad no puede superar el stock disponible (${stock}).`);
            return;
        }


        let carritoLocalStorage = JSON.parse(localStorage.getItem('carrito')) || [];

        const productoExistente = carritoLocalStorage.find(item => item.id === id);

        if (productoExistente) {

            if (productoExistente.cantidad + cantidad > stock) {
                mostrarModalError(`La cantidad no puede superar el stock disponible (${stock}).`);
                return;
            }
            productoExistente.cantidad += cantidad;
        } else {

            let carritoModal = new bootstrap.Modal(document.getElementById("modalCarrito"));
            let carrito = document.getElementById("modalCarrito")
            carrito.querySelector(".modal-body").innerHTML = "Se ha añadido al carrito exitosamente: <br>" + marca + " " + modelo
            carrito.querySelector("div.cantidadCarrito").textContent = "Cantidad: " + cantidad
            carritoModal.show();
            
            const productoConCantidad = {
                id: id,
                cantidad: cantidad,
                precioEnPesos: precioEnPesos,
                marca: marca,
                modelo: modelo,
                imagen: imagen
            };
            carritoLocalStorage.push(productoConCantidad);
        }

        localStorage.setItem('carrito', JSON.stringify(carritoLocalStorage));

        console.log("Carrito actualizado:");
        carritoLocalStorage.forEach(item => {
            console.log(`ID: ${item.id}, Cantidad: ${item.cantidad}, Precio Unitario: ${item.precioEnPesos}`);
        });
    }

    loadOfertasSemana();
});