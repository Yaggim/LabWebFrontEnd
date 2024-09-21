const productos = [
    {
        "id": 1,
        "brand": {
            "id": 1,
            "name": "Kingston"
        },
        "model": {
            "id": 1,
            "name": "HyperX Fury DDR4"
        },
        "category": {
            "id": 1,
            "name": "Memorias"
        },
        "priceARS": 1000,
        "priceUSD": 10,
        "enabled": true,
        "stock": 10,
        "image": [
            "../images/memoria1.jpg",
            "../images/memoria2.jpg",
            "../images/memoria3.jpg"
        ],
        "description": "Si tu computadora funciona con lentitud, si un programa no responde o no se carga, lo más probable es que se trate de un problema de memoria. Estos son posibles indicios de un rendimiento defectuoso en el día a día de tus tareas. Por ello, contar con una memoria Kingston -sinónimo de trayectoria y excelencia- mejorará la productividad de tu equipo: las páginas se cargarán más rápido y la ejecución de nuevas aplicaciones resultará más ágil y simple."
    }
];

// Función para buscar un producto por su ID y cargar los datos al HTML
function cargarProductoPorID(idProducto) {
    // Buscar el producto por su ID
    const producto = productos.find(prod => prod.id === idProducto);
    let divStock = document.getElementById("divStock");

    if (producto) {
        document.getElementById("producto-id").textContent = "Código de identificación de producto: " + producto.id;
        document.getElementById("producto-brand-model").textContent = producto.brand.name + " " + producto.model.name;
        document.getElementById("producto-category").textContent = "Categoría: " + producto.category.name;
        document.getElementById("producto-priceARS").textContent = "Precio ARS: $" + producto.priceARS; //+ " / USD: $" + producto.priceUSD;
        //document.getElementById("producto-stock").textContent = "Stock: " + producto.stock + " unidades";
        document.getElementById("producto-image1").src = producto.image[0];
        document.getElementById("producto-image2").src = producto.image[1];
        document.getElementById("producto-image3").src = producto.image[2];
        document.getElementById("producto-description").textContent = producto.description;
        if(producto.stock < 10){
            document.querySelector("#divStock h2").innerHTML = "BAJO"
            divStock.style.backgroundColor = "#e72c2c"
        }
        if(producto.stock >= 10){
            document.querySelector("#divStock h2").innerHTML = "ALTO"
            divStock.style.backgroundColor = "#7ac94c"
        }
    } else {
        console.error("Producto no encontrado.");
    }
}

// Llamada a la función con el ID del producto
cargarProductoPorID(1);

//Comprar
document.getElementById("btnCompra").addEventListener("click", realizarCompra);
function realizarCompra() {
    //evaluar el stock de la bbdd >= const cantidad = document.getElementById('quantity');
    alert("Se redirige a la pasarela de pago.");
}

document.getElementById("formEnvio").addEventListener("submit", function (event) {
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

//Agregar al carrito FALTA QUE AGREGUE AL MODAL LA LEYENDA DE BRAND + MODEL
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