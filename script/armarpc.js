let cpu_intel = "https://s3-sa-east-1.amazonaws.com/saasargentina/NcQz90NWK5mHqqyfmf9N/miniatura";

let descripcion_cpu ="Micro Intel Core I9 14900F 24 Núcleos / 32 Threads 5.8Ghz (14va Gen) LGA1700";




function manejadorDeMocks () {
	//borrar contenido actual

	let contenedor_cpu = document.getElementById("cpu");
	
	contenedor_cpu.remove();

	//mocks
	mockCpu();
	
}

function mockCpu(){
    
    
    let actualizar = document.getElementById("actualizar");


	for(let i = 0; i < 3; i++){

		let fila = document.createElement("div");
		fila.className ="row";
		actualizar.appendChild(fila);
		
		for(let j = 0; j < 3; j++){
			let col = document.createElement("div");
			col.className="col-md-4 mb-4";
			//columnas.push(col);
			
			//card
			let card = document.createElement("div");
			card.className="card m-auto";

			//imagen card
			let imagen = document.createElement("img");
			imagen.className ="card-img-top";
			imagen.src = cpu_intel;
			

			//card-body
			let card_body = document.createElement("div");
			card_body.className="card-body";

			//descripcion
			let desc = document.createElement("p");
			desc.className ="card-text";
			desc.textContent= descripcion_cpu;

			//card-footer
			let card_footer = document.createElement("div");
			card_footer.className= "card-footer";

			//btn-añadir
			let btn_aniadir = document.createElement("button");
			btn_aniadir.className = "btn btn-primary w-100";
			btn_aniadir.textContent = "Añadir";

			card_body.appendChild(desc);
			card_footer.appendChild(btn_aniadir);
			card.appendChild(imagen);
			card.appendChild(card_body);
			card.appendChild(card_footer);
			col.appendChild(card);
			fila.appendChild(col);
		}

		
		
	}



}








window.onload = ()=> {
	let cpu_btn = document.getElementById("cpu");

	cpu_btn.addEventListener("click", manejadorDeMocks);
}