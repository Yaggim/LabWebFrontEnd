

let errores = {};

let hayErrores = false;


function manejadorDeValidaciones(e){
	e.preventDefault();

	console.log("ValidacionesOk");

	errores.nombre   = validarNombreOApellido(1);
	errores.apellido = validarNombreOApellido(2);
	errores.dni      = validarDni();
	errores.tel      = validarTelefono();
}



function validarNombreOApellido(caso){

	if(caso == 1){
		let campoNombreOApellido = String(document.getElementById("nombre").value);
	}else{
		let campoNombreOApellido = String(document.getElementById("apellido").value);
	}
	

	let nombreRegex = /^[a-zA-Z]+$/;

	let splitCampo = campoNombreOApellido.split(' ');



	if(campoNombreOApellido.length > 40){
		return "El máximo de caracteres es 40!!!";
	}

	if(campoNombreOApellido == ""){
		return "Debe completar este campo!!!";
	}

	for(palabra of splitCampo){

		if(!nombreRegex.test(palabra)){
			return "El campo debe contener solo caracteres alfabéticos!!!";
			break;
		}
	}

	return null;
	
}




function validarDni(){

	let campoDni = String(document.getElementById("dni").value);
	let dniRegex = /^\d+$/;
	let dniNum   = parseInt(campoDni);

	if(campoDni == ""){
		return "Debe completar el campo DNI!!!";
	}

	if(!dniRegex.test(campoDni)){
		return "Solo caracteres numéricos!!!";
	}

	if(dniNum < 3000000 || dniNum > 99999999){
		return "Solo dni entre 3.000.000 y 99.999.999";
	} 

	return null;
}





function validarTelefono(){

	let campoTel     = String(document.getElementById("telefono").value);
	let telRegex     = /^\d{2}-\d{4}-\d{4}$/;
	 

	if(campoTel == ""){
		return "Debe completar el campo teléfono!!!";
	}

	if(!telRegex.test(campoTel)){
		return "El formato debe ser 11-1111-1111 y solo dígitos!!!";
	}

	return null;
}









window.onload = ()=>{
	let formulario = document.getElementById("form");

	formulario.addEventListener("submit", manejadorDeValidaciones);
}