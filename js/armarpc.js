let archivos = ['../jsons/cpu.json', '../jsons/cooler.json', 
				'../jsons/mother.json','../jsons/memoria.json',
				'../jsons/video.json','../jsons/disco.json',
				'../jsons/fuente.json','../jsons/gabinete.json','../jsons/cpu.json'];

let prodSelecc = [];

let parcial = 0;

let index= 0;

function cargarJsons(index) {
	
  
	if (index == archivos.length - 1){
       
        crearInforme();
        return;
    }

    fetch(archivos[index])
        .then(response => {
            // Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json(); 
        })
        .then(data => {
            
            mockProducto(data);
        })
        .catch(error => {
            
            console.error('Hubo un problema con la operación fetch:', error);
        });

   
}

function mockProducto(data) {
    let actualizar = document.getElementById("actualizar");
    actualizar.innerHTML = ""; 

    let fila;

   
    data.forEach((producto, index) => {
        // Crear una nueva fila cada tres productos
        if (index % 3 === 0) {
            fila = document.createElement("div");
            fila.className = "row"; // Clase Bootstrap para fila
            actualizar.appendChild(fila);
        }

        let col = document.createElement("div");
        col.className = "col-md-4 mb-4"; // Clase Bootstrap para columnas
        
        
        let card = document.createElement("div");
        card.className = "card m-2";
        card.style     = "width: 22rem;"

       
        let imagen = document.createElement("img");
        imagen.className = "card-img-top";
        imagen.src = producto.ruta_imagen; // Usar ruta de imagen del producto

       
        let card_body = document.createElement("div");
        card_body.className = "card-body";

        
        let desc = document.createElement("p");
        desc.className = "card-text";
        desc.textContent = producto.descripcion; // Usar descripción del producto

      
        let impor = document.createElement("p");
        impor.className = "importe text-center";
        impor.textContent = "$" + producto.valor; // Usar valor del producto

        
        let card_footer = document.createElement("div");
        card_footer.className = "card-footer";

        
        let btn_aniadir = document.createElement("button");
        btn_aniadir.className = "btn btn-primary w-100";
        btn_aniadir.textContent = "Añadir";
        btn_aniadir.dataset.valor = producto.valor; 
        btn_aniadir.addEventListener("click", () => {
        	prodSelecc.push(producto);
        	parcial += producto.valor;
            document.getElementById("cart-total").textContent = parcial;
            siguienteProducto();

        });

        
        card_body.appendChild(desc);
        card_body.appendChild(impor);
        card_footer.appendChild(btn_aniadir);
        card.appendChild(imagen);
        card.appendChild(card_body);
        card.appendChild(card_footer);
        col.appendChild(card);
        fila.appendChild(col);
    });

    
    
    
}



function siguienteProducto(){
	index++;

	if (index <= archivos.length) {
        cargarJsons(index);
    }
}



function crearInforme(){

   
    console.log("TEST INFORME!!!");

    let actualizar = document.getElementById("actualizar");
    actualizar.innerHTML = ""; 


    actualizar.className += 'container mt-4';

   
    let titulo = document.createElement('h3');
    titulo.textContent = "Presupuesto";
    actualizar.appendChild(titulo);

    
    let tabla = document.createElement('table');
    tabla.className = 'table table-striped';

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

   
    prodSelecc.forEach(producto => {
        let fila = document.createElement('tr');

        let tdImagen = document.createElement('td');
        let img = document.createElement('img');
        img.src = producto.ruta_imagen;
        img.className = 'img-thumbnail';
        img.style.width = '100px';
        tdImagen.appendChild(img);
        fila.appendChild(tdImagen);

        let tdDescripcion = document.createElement('td');
        tdDescripcion.textContent = producto.descripcion;
        fila.appendChild(tdDescripcion);

        let tdValor = document.createElement('td');
        tdValor.textContent = '$' + producto.valor;
        fila.appendChild(tdValor);

        tbody.appendChild(fila);
    });

    let filaTotal = document.createElement('tr');
    let tdTotalLabel = document.createElement('td');
    tdTotalLabel.colSpan = 2;
    tdTotalLabel.textContent = "Total";
    tdTotalLabel.style.fontWeight = "bold";
    filaTotal.appendChild(tdTotalLabel);

    let tdTotalValue = document.createElement('td');
    tdTotalValue.textContent = '$' + parcial;
    tdTotalValue.style.fontWeight = "bold";
    filaTotal.appendChild(tdTotalValue);

    tbody.appendChild(filaTotal);

    tabla.appendChild(tbody);
   
    let btnComprar = document.createElement('button');
    btnComprar.className = 'btn btn-success w-100 mt-3';
    btnComprar.textContent = "Comprar";
    btnComprar.addEventListener('click', () => {
        window.location.href = '../views/compra.html';
    });

    
    actualizar.appendChild(tabla);
    actualizar.appendChild(btnComprar);
    
    borrarCart();
  
}



function borrarCart(){

    let cart = document.getElementById("cart");
    cart.style.display = "none";
}


window.onload = () => {
    
	let cpu1 = document.getElementById("cpu1");
	let cpu2 = document.getElementById("cpu2");

	cpu1.addEventListener("click", ()=>{
		cargarJsons(index);
	});

	cpu2.addEventListener("click", ()=>{
		cargarJsons(index);
	});
    
}
