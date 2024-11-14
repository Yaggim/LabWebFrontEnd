<h5 class="mt-2 mb-3 login-title text-center"><span>Bienvenido/a, <?php echo $_SESSION['usuario']['nombre']; ?></span></h5>

<div class="text-center justify-content-center">
    <img src="/<?php echo CARPETA_PROYECTO ?>/images/user-profile.png" width="120" alt="imagen de perfil genérica">
</div>

<div class="d-flex flex-column align-items-center justify-content-center">
    <div class="input-group mt-3">
        <button type="button" class="col-6 btn btn-custom mb-3 mx-auto" id="btnPerfil">Mi perfil</button>
    </div>
    <div class="input-group mt-3">
        <button type="button" class="col-6 btn btn-custom mb-3 mx-auto btnEditarPerfil">Editar perfil</button>
    </div>
    <?php if (Permisos::esRol('administrador', $_SESSION['usuario']['id']) ): ?>
    <div class="input-group mt-3">
        <button type="button" class="col-6 btn btn-custom mb-3 mx-auto" id="btnAdmin">Panel admin</button>
    </div>
    <?php endif; ?>  
    <div class="input-group mt-3">
        <button type="submit" class="col-6 btn btn-custom mb-3 mx-auto" name="action" value="logout" id="btnLogout">Cerrar sesión</button>
    </div>
</div>
