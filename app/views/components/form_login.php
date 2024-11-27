<h5 class="mb-4 login-title"><span>Inicia Sesión</span></h5>
<div class="mb-3">
    <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
    <input type="text" class="form-control" id="nombreUsuario" name="username" placeholder="Usuario123">
    <div id="errorLoginUsername" class="invalid-feedback"></div>
</div>
<div class="mb-3">
    <label for="passwordSesion" class="form-label">Contraseña</label>
    <input type="password" class="form-control" id="passwordSesion" name="password" placeholder="***********">
    <div id="errorLoginPassword" class="invalid-feedback"></div>
</div>
<div class="text-center" >
    <button type="submit" class="btn btn-custom mb-3 mt-2" name="action" value="login" id="btnLogin" >Iniciar sesión</button>
</div>
<div class="mt-3">
    <a href="/<?php echo CARPETA_PROYECTO ?>/registro">¿No tenés cuenta? <strong>Registrate</strong></a>
</div>
