let archivos = ['../jsons/cpu.json', '../jsons/cooler.json', 
				'../jsons/mother.json','../jsons/memoria.json',
				'../jsons/video.json','../jsons/disco.json',
				'../jsons/fuente.json','../jsons/gabinete.json'];

let prodSelecc = [];

let parcial = 0;

let index= 0;

function cargarJsons(index) {
	
	if (index >= archivos.length) return;

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

   // crearInforme();
}

function mockProducto(data) {
    let actualizar = document.getElementById("actualizar");
    actualizar.innerHTML = ""; // Limpiar el contenido actual

    let fila; // Variable para la fila actual

    // Iterar sobre cada producto en el array data
    data.forEach((producto, index) => {
        // Crear una nueva fila cada tres productos
        if (index % 3 === 0) {
            fila = document.createElement("div");
            fila.className = "row"; // Clase Bootstrap para fila
            actualizar.appendChild(fila);
        }

        let col = document.createElement("div");
        col.className = "col-md-4 mb-4"; // Clase Bootstrap para columnas
        
        // Crear la tarjeta
        let card = document.createElement("div");
        card.className = "card m-2";
        card.style     = "width: 22rem;"

        // Imagen de la tarjeta
        let imagen = document.createElement("img");
        imagen.className = "card-img-top";
        imagen.src = producto.ruta_imagen; // Usar ruta de imagen del producto

        // Card-body
        let card_body = document.createElement("div");
        card_body.className = "card-body";

        // Descripción
        let desc = document.createElement("p");
        desc.className = "card-text";
        desc.textContent = producto.descripcion; // Usar descripción del producto

        // Importe
        let impor = document.createElement("p");
        impor.className = "importe text-center";
        impor.textContent = "$" + producto.valor; // Usar valor del producto

        // Card-footer
        let card_footer = document.createElement("div");
        card_footer.className = "card-footer";

        // Botón añadir
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

        // Añadir elementos a la tarjeta
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

	if (index < archivos.length) {
        cargarJsons(index);
    }
}

function crearInforme(){
	let actualizar = document.getElementById("actualizar");
    actualizar.innerHTML = ""; 

     // Crear el contenedor de la tabla
    let contenedorTabla = document.createElement('div');
    contenedorTabla.className = 'container';

    // Crear las filas de productos
    prodSelecc.forEach(producto => {
        // Crear una tarjeta por cada producto
        let card = document.createElement('div');
        card.className = 'card mb-3';

        // Crear el cuerpo de la tarjeta
        let cardBody = document.createElement('div');
        cardBody.className = 'card-body';

        // Crear la imagen de la tarjeta
        let img = document.createElement('img');
        img.className = 'card-img-top';
        img.src = producto.ruta_imagen;
        card.appendChild(img);

        // Crear la descripción
        let descripcion = document.createElement('p');
        descripcion.className = 'card-text';
        descripcion.textContent = producto.descripcion;
        cardBody.appendChild(descripcion);

        // Crear el valor
        let valor = document.createElement('p');
        valor.className = 'card-text';
        valor.textContent = 'Valor: $' + producto.valor;
        cardBody.appendChild(valor);

        // Agregar el cuerpo de la tarjeta a la tarjeta
        card.appendChild(cardBody);

        // Agregar la tarjeta al contenedor de la tabla
        contenedorTabla.appendChild(card);
    });

    // Insertar el contenedor de la tabla en el cuerpo del documento
    document.body.appendChild(contenedorTabla);
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
