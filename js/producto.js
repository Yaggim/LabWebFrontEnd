//Insertar imagenes del producto producto.json "image"

//Insertar HTML de descripción del producto producto.json "description"

//Insertar HTML del ID del producto producto.json "id"

//Insertar HTML de la marca + modelo producto.json "brand" "model"

//Insertar HTML de categoría producto.json "category"

//Insertar HTML de precio producto.json "priceARS"

//Manejo de clase de divStock según el stock producto.json "stock"
/*
stock bajo #e72c2c
stock intermedio #eeee1e
stock alto #7ac94c
*/

//Comprar
document.getElementById("btnCompra").addEventListener("click", realizarCompra);
function realizarCompra() {
    //evaluar el stock de la bbdd >= const cantidad = document.getElementById('quantity');
    alert("Se redirige a la pasarela de pago.");
}

document.getElementById("formEnvio").addEventListener("submit", function(event) {
    event.preventDefault();
    
    let calle = document.getElementById("envioCalle").value;
    let altura = document.getElementById("envioAltura").value;
    let postal = document.getElementById("envioPostal").value;
    let nota = document.getElementById("envioNota").value;
    console.log("Calle:", calle, "Altura:", altura, "Código Postal:", postal, "Nota:", nota);
    alert("Envío cargado satisfactoriamente!");
    let envioModal = bootstrap.Modal.getInstance(document.getElementById("modalEnvio"));
    envioModal.hide();
});

//Enviar a domicilio
document.getElementById("btnEnvio").addEventListener("click", envioDomicilio);
function envioDomicilio() {
    let envioModal = new bootstrap.Modal(document.getElementById("modalEnvio"));
    envioModal.show();
}

//Agregar al carrito
document.getElementById("btnCarrito").addEventListener("click", agregarAlCarrito);
function agregarAlCarrito() {
    let carritoModal = new bootstrap.Modal(document.getElementById("modalCarrito"));
    carritoModal.show();
}

//Incremento y decremento de cantidad del producto
const decrement = document.getElementById('btnDecrement');
const increment = document.getElementById('btnIncrement');
const cantidad = document.getElementById('quantity');
decrement.addEventListener('click', function incrementar() {
    let valorActual = parseInt(cantidad.value);
    if (valorActual > 1) {
        cantidad.value = valorActual - 1;
    }
});
//Falta condicionar la cantidad sujeto al stock del producto producto.json "stock"
increment.addEventListener('click', function decrementar() {
    let valorActual = parseInt(cantidad.value);
    cantidad.value = valorActual + 1;
});