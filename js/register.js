

let errores = {};

let hayErrores;

let pass; 


function manejadorDeValidaciones(e){
	e.preventDefault();

	hayErrores = false;
	
	limpiarErrores();
	
	errores.error_nombre    = validarNombreOApellido(1);
	errores.error_apellido  = validarNombreOApellido(2);
	errores.error_dni       = validarDni();
	errores.error_tel       = validarTelefono();
	errores.error_user      = validarUser();
	errores.error_email     = validarEmail();
	errores.error_pass      = validarPass();
	errores.error_pass_conf = validarPassConf();
	errores.error_terminos  = validarTerminos();


	renderDeErrores();


	if(!hayErrores){
		lanzarModal();
	}

	console.log(hayErrores);
}



function lanzarModal(){

    var modalE = document.getElementById('successModal');
    var modal = new bootstrap.Modal(modalE);
       
    modal.show();
	resetearForm();
}

function renderDeErrores(){

	for (let clave in errores) {
    	
		if (errores.hasOwnProperty(clave)) {
        	if(errores[clave] != null){
        		hayErrores = true;
        	}

        	document.getElementById(clave).textContent = errores[clave];
    	}
	}
}


function limpiarErrores(){

	let divError = document.getElementsByClassName("error");

	for(let elemento in divError){
		elemento.textContent = "";
	}
}



function resetearForm(){
	
	let form = document.getElementById('form');
    
    limpiarErrores();
    form.reset();
}


function validarNombreOApellido(caso){


	let campoNombreOApellido;

	if(caso == 1){
		campoNombreOApellido = String(document.getElementById("nombre").value);
	}else{
		campoNombreOApellido = String(document.getElementById("apellido").value);
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






function validarUser(){
	let campoUser = String(document.getElementById("username").value);
	let userRegex =  /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;


	if(campoUser == ""){
		return "Debe completar em campo usuario!!!";
	}

	if(campoUser.length < 8 || campoUser > 15){
		return "El nombre de usuario debe tener entre 8 y 15 caracteres!!!";
	}

	if(!userRegex.test(campoUser)){
		return "El nombre de usuario debe contener letras, números y al menos un símbolo!!!";
	}


	userName = campoUser;

	return null;

}


function validarEmail(){
	
	let campoEmail = String(document.getElementById("email").value);
	let emailRegex =  /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


	if(campoEmail == ""){
		return "Debe completar el campo email!!!";
	}


	if(!emailRegex.test(campoEmail)){
		return "El email debe ser de la forma holamundo@abc.fgh!!!";
	}

	return null;
}



function validarPass(){
	let campoPass = String(document.getElementById("password").value);
	let passRegex =  /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;


	if(campoPass == ""){
		return "Debe completar em campo password!!!";
	}

	if(campoPass.length < 8 || campoPass.length > 15){
		return "La contraseña debe tener entre 8 y 15 caracteres!!!";
	}

	if(!passRegex.test(campoPass)){
		return "La contraseña debe contener letras, números y al menos un símbolo!!!";
	}


	pass = campoPass;

	return null;
}


function validarPassConf(){
	let campoPass = String(document.getElementById("confirm_password").value);
	


	if(campoPass == ""){
		return "Debe completar em campo confirmar password!!!";
	}

	if(pass != campoPass){
		return "La contraseña no coincide!!!";
	}


	return null;
}


function validarTerminos(){
	let terminos = document.getElementById("terminos");

	if(!terminos.checked){
		return "Debe aceptar los términos y condiciones!!!";
	}

	return null;
}


window.onload = ()=>{
	let formulario = document.getElementById("form");

	formulario.addEventListener("submit", manejadorDeValidaciones);
}