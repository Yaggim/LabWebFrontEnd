

//categorías en orden para amar tu Pc

let categorias = ['CPUs', 'Coolers','Mothers','Memorias',
                'Placas De Video','Discos',
                'Fuentes','Gabinetes',
                'Monitores'];



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
            
            //console.log(productos);
            RenderProducto(productos);
        }
    }
   
}




//render dinámico de productos

async function RenderProducto(productos) {
    let actualizar = document.getElementById("actualizar");
    
    //borra la zona a actualizar
    actualizar.innerHTML = ""; 

    let fila;

   
    for (let index = 0; index < productos.length; index++) {
        const producto = productos[index];
        const precioEnPesos = await actualizarPrecioARS(parseFloat(producto.precio));
        
        //4 productos por filas
        if (index % 4 === 0) {
            fila = document.createElement("div");
            fila.className = "row"; 
            actualizar.appendChild(fila);
        }

        //creación de tags
        let col = document.createElement("div");
        col.className = "col-12 col-sm-6 col-md-6 col-lg-3 mb-4"; 
        
        let card = document.createElement("div");
        card.className = "card m-2";
        card.style = "width: 100%;"

        let imagen = document.createElement("img");
        imagen.className = "card-img-top";
        imagen.src = 'public/' + producto.imagen; 
       

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

        //console.log(producto);

        //más de un mismo producto si ...
        if (producto.categoria === "Memorias" || producto.categoria === "Discos" ||
            producto.categoria === "Monitores") {
            cantidadInput = document.createElement("input");
            cantidadInput.type = "number";
            cantidadInput.className = "form-control";
            cantidadInput.min = 1;
            cantidadInput.max = producto.stock;
            cantidadInput.step = 1;
            cantidadInput.value = 1; 

            card_footer.appendChild(cantidadInput);
        }

        let btn_aniadir = document.createElement("button");
        btn_aniadir.className = "btn btn-primary w-100 mt-2";
        btn_aniadir.textContent = "Añadir";

        //pasaje a pesos
        btn_aniadir.dataset.valor = precioEnPesos.toFixed(2);

       
        btn_aniadir.addEventListener("click", async () => {
            
            const cantidad = (cantidadInput) ? parseInt(cantidadInput.value) : 1;
            
            
            const totalProducto = precioEnPesos * cantidad;

            //llama a modal si cantidad seleccionada excede el stock
            if (cantidad > producto.stock) {
                mostrarModalError(`La cantidad no puede superar el stock disponible (${producto.stock}).`);
                return;
            }
            

            //guardado de producto seleccionado
            for (let i = 0; i < cantidad; i++) {
                prodSelecc.push({
                    ...producto,
                    cantidad: 1, 
                    precioEnPesos: precioEnPesos
                });
            }

            //actualiza el parcial de compra
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

    
    //console.log(prodSelecc);
}

//modal error cantidad
function mostrarModalError(mensaje) {
    const modalError = new bootstrap.Modal(document.getElementById('modalError'));
    document.getElementById('modalErrorMensaje').textContent = mensaje;
    modalError.show();

    
    document.getElementById('modalErrorCerrar').addEventListener('click', () => {
        modalError.hide();
    });
}


//funcion para recorrer las categorias 
function siguienteProducto(){
	
  // console.log(categorias.length);

	if (index < categorias.length) {
         index++;
         pasos++;
        cargarJsons(index);
    }else{
        crearInforme();
    }
}





//funcion para crear el informe de compra

async function crearInforme() {

    let actualizar = document.getElementById("actualizar");
    actualizar.innerHTML = ""; 

    actualizar.className += ' justify-content-center';

    //creación de tags
    let titulo = document.createElement('h2');
    titulo.textContent = "Presupuesto";
    titulo.className += "text-center";
    actualizar.appendChild(titulo);

    
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

    //creación de tabla y agregado de tds
    
    for (let producto of prodSelecc) {
        let fila = document.createElement('tr');

        let tdImagen = document.createElement('td');
        let img = document.createElement('img');
        img.src = 'public/' + producto.imagen;
        img.className = 'img-thumbnail';
        img.style.width = '100px';
        tdImagen.appendChild(img);
        fila.appendChild(tdImagen);

        let tdDescripcion = document.createElement('td');
        tdDescripcion.textContent = producto.descripcion;
        fila.appendChild(tdDescripcion);

        
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

    
    let btnContainer = document.createElement('div');
    btnContainer.className = 'd-flex justify-content-center mt-3'; 

    let btnComprar = document.createElement('button');
    btnComprar.className = 'btn btn-success w-25';
    btnComprar.textContent = "Ver en carrito";
   
    //si compra guarda los items en el ls
    btnComprar.addEventListener('click', () => {
	const armarPc = JSON.stringify(prodSelecc);
	localStorage.setItem('carrito', armarPc);    
        const basePath = window.location.pathname.split('/')[1];
        window.location.href = `/${basePath}/carrito`;
    });

    btnContainer.appendChild(btnComprar); 
    actualizar.appendChild(btnContainer); 
}






function borrarCart(){

    let cart = document.getElementById("cart");
    cart.style.display = "none";
}


//muestra paso actual
function actualizarPaso(){
    let pasoEle = document.getElementById("paso-actual");
    pasoEle.innerHTML = pasos;
}


//api dolor blue
async function fetchDolarBlue() {
    const response = await fetch("https://dolarapi.com/v1/dolares/blue");
    const data = await response.json();
    dolarBlue = data.venta;
    return parseFloat(dolarBlue);
}


//a pesos
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
    	

    

	cpu1.addEventListener("click", (evento)=>{
		cargarJsons(index,evento);
	});

	cpu2.addEventListener("click", (evento)=>{
		cargarJsons(index,evento);
	});
    
}
