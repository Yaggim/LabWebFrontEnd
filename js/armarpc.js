




let categorias = ['CPUs', 'Coolers','Mothers','Memorias',
                'Placas De Video'];/*,'Discos',
                'Fuentes','Gabinetes',
                'Monitores'];*/



let prodSelecc = [];
let parcial    = 0;
let index      = 0;
let pasos      = 1;
let marca      = "";
let cateActual = "";
let url        = ""; 



function cargarJsons(index,evento) {
	
    actualizarPaso();

	if (index == categorias.length){
       
        crearInforme();
        return;
    }

    if(pasos == 1){
        marca = evento.target;
    }else{
        marca.name = "";
    }


        cateActual = categorias[index];

        const basePath = window.location.pathname.split('/')[1];

        
        let url = "/"+ basePath + "/includes/armarPcBBDD.php?categoria="+ cateActual + "&marca=" + marca.name;
        
        console.log(url);
        const xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.send();

        xhr.onload = ()=>{
        if(xhr.status === 200){
            const productos = JSON.parse(xhr.responseText);
            
            console.log(productos);
            RenderProducto(productos);
        }
    }
   
}




async function RenderProducto(productos) {
    let actualizar = document.getElementById("actualizar");
    actualizar.innerHTML = ""; 

    let fila;

    for (let index = 0; index < productos.length; index++) {
        const producto = productos[index];
        const precioEnPesos = await actualizarPrecioARS(parseFloat(producto.precio));

        
        if (index % 4 === 0) {
            fila = document.createElement("div");
            fila.className = "row"; 
            actualizar.appendChild(fila);
        }

        let col = document.createElement("div");
        col.className = "col-12 col-sm-6 col-md-6 col-lg-3 mb-4"; 
        
        let card = document.createElement("div");
        card.className = "card m-2";
        card.style = "width: 100%;"

        let imagen = document.createElement("img");
        imagen.className = "card-img-top";
        imagen.src = producto.imagen; 

        let card_body = document.createElement("div");
        card_body.className = "card-body";

        let marca = document.createElement("p");
        marca.className = "card-text";
        marca.textContent = producto.marca;
         
        let desc = document.createElement("p");
        desc.className = "card-text";
        desc.textContent = producto.descripcion; 

        let impor = document.createElement("p");
        impor.className = "importe text-center";
        impor.textContent = "$" + precioEnPesos.toFixed(2); 

        let card_footer = document.createElement("div");
        card_footer.className = "card-footer";


        
        let cantidadInput;

        console.log(producto);

        if (producto.categoria === "Memorias" || producto.categoria === "Discos" ||
            producto.categoria === "Monitores") {
            cantidadInput = document.createElement("input");
            cantidadInput.type = "number";
            cantidadInput.className = "form-control";
            cantidadInput.min = 1;
            cantidadInput.max = 6;
            cantidadInput.step = 1;
            cantidadInput.value = 1; 

            card_footer.appendChild(cantidadInput);
        }

        let btn_aniadir = document.createElement("button");
        btn_aniadir.className = "btn btn-primary w-100 mt-2";
        btn_aniadir.textContent = "Añadir";
        btn_aniadir.dataset.valor = precioEnPesos.toFixed(2);

       
        btn_aniadir.addEventListener("click", async () => {
            
            const cantidad = (cantidadInput) ? parseInt(cantidadInput.value) : 1;
            
            
            const totalProducto = precioEnPesos * cantidad;

            
            for (let i = 0; i < cantidad; i++) {
                prodSelecc.push({
                    ...producto,
                    cantidad: 1 
                });
            }

            
            parcial += totalProducto;
            document.getElementById("cart-total").textContent = parcial.toFixed(2);
            siguienteProducto();
        });

        card_body.appendChild(marca);
        card_body.appendChild(desc);
        card_body.appendChild(impor);
        card_footer.appendChild(btn_aniadir);

        card.appendChild(imagen);
        card.appendChild(card_body);
        card.appendChild(card_footer);
        col.appendChild(card);
        fila.appendChild(col);
    }

    
    console.log(prodSelecc);
}







function siguienteProducto(){
	
   console.log(categorias.length);

	if (index < categorias.length) {
         index++;
         pasos++;
        cargarJsons(index);
    }else{
        crearInforme();
    }
}







async function crearInforme() {

    let actualizar = document.getElementById("actualizar");
    actualizar.innerHTML = ""; 

    actualizar.className += 'container mt-4';

    let titulo = document.createElement('h3');
    titulo.textContent = "Presupuesto";
    actualizar.appendChild(titulo);

    // Contenedor para centrar la tabla
    let tablaContainer = document.createElement('div');
    tablaContainer.className = 'd-flex justify-content-center'; 

    let tabla = document.createElement('table');
    tabla.className = 'table table-striped';
    tabla.style.maxWidth = '70%'; 

    let thead = document.createElement('thead');
    let encabezadoFila = document.createElement('tr');

    let thImagen = document.createElement('th');
    thImagen.textContent = "Foto";
    encabezadoFila.appendChild(thImagen);

    let thDescripcion = document.createElement('th');
    thDescripcion.textContent = "Descripción";
    encabezadoFila.appendChild(thDescripcion);

    let thValor = document.createElement('th');
    thValor.textContent = "Valor";
    encabezadoFila.appendChild(thValor);

    thead.appendChild(encabezadoFila);
    tabla.appendChild(thead);

    let tbody = document.createElement('tbody');

    
    for (let producto of prodSelecc) {
        let fila = document.createElement('tr');

        let tdImagen = document.createElement('td');
        let img = document.createElement('img');
        img.src = producto.imagen;
        img.className = 'img-thumbnail';
        img.style.width = '100px';
        tdImagen.appendChild(img);
        fila.appendChild(tdImagen);

        let tdDescripcion = document.createElement('td');
        tdDescripcion.textContent = producto.descripcion;
        fila.appendChild(tdDescripcion);

        // Espera a que se resuelva la promesa de actualizarPrecioARS
        let precioEnPesos = await actualizarPrecioARS(parseFloat(producto.precio));
        let tdValor = document.createElement('td');
        tdValor.textContent = '$' + precioEnPesos.toFixed(2);
        fila.appendChild(tdValor);

        tbody.appendChild(fila);
    }

    let filaTotal = document.createElement('tr');
    let tdTotalLabel = document.createElement('td');
    tdTotalLabel.colSpan = 2;
    tdTotalLabel.textContent = "Total";
    tdTotalLabel.style.fontWeight = "bold";
    filaTotal.appendChild(tdTotalLabel);

    let tdTotalValue = document.createElement('td');
    tdTotalValue.textContent = '$' + parcial.toFixed(2);
    tdTotalValue.style.fontWeight = "bold";
    filaTotal.appendChild(tdTotalValue);

    tbody.appendChild(filaTotal);

    tabla.appendChild(tbody);

    tablaContainer.appendChild(tabla); 
    actualizar.appendChild(tablaContainer); 

    // Contenedor para centrar el botón de compra
    let btnContainer = document.createElement('div');
    btnContainer.className = 'd-flex justify-content-center mt-3'; 

    let btnComprar = document.createElement('button');
    btnComprar.className = 'btn btn-success w-25';
    btnComprar.textContent = "Comprar";
    btnComprar.addEventListener('click', () => {
	const armarPc = JSON.stringify(prodSelecc);
	localStorage.setItem('armarPcJson', armarPc);    
        window.location.href = 'finalizar-compra';
    });

    btnContainer.appendChild(btnComprar); 
    actualizar.appendChild(btnContainer); 
}






function borrarCart(){

    let cart = document.getElementById("cart");
    cart.style.display = "none";
}


function actualizarPaso(){
    let pasoEle = document.getElementById("paso-actual");
    pasoEle.innerHTML = pasos;
}


async function fetchDolarBlue() {
    const response = await fetch("https://dolarapi.com/v1/dolares/blue");
    const data = await response.json();
    dolarBlue = data.venta;
    return parseFloat(dolarBlue);
}

async function actualizarPrecioARS(precio) {
    try {
        const dolarBlue = await fetchDolarBlue();
        const precioEnPesos = precio * dolarBlue;
        return precioEnPesos;
    } catch (error) {
        console.error("Error al obtener el precio en pesos:", error);
    }
}




window.onload = () => {
    
	let cpu1 = document.getElementById("cpu1");
	let cpu2 = document.getElementById("cpu2");
    	let atrasBtn = document.getElementById("backBtn");

    	atrasBtn.style.display = "none";

	cpu1.addEventListener("click", (evento)=>{
		cargarJsons(index,evento);
	});

	cpu2.addEventListener("click", (evento)=>{
		cargarJsons(index,evento);
	});
    
}
