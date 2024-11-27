    <nav id="sidebar" class="bg-dark text-white p-3">
        <h3 class="text-center">Admin Panel</h3>
        <ul class="nav flex-row mt-4">
            <?php if (Permisos::tienePermiso('Crear producto', $_SESSION['usuario']['id']) ): ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/marca"><i class="fas fa-tags me-2"></i>Marcas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/modelo"><i class="fas fa-cubes me-2"></i>Modelos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/categoria"><i class="fas fa-list-alt me-2"></i>Categorías</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/productosAdmin"><i class="fas fa-box-open me-2"></i>Productos</a>
            </li>
            <?php endif; ?>  
            <?php if (Permisos::tienePermiso('Modificar stock', $_SESSION['usuario']['id']) ): ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/stock"><i class="fas fa-warehouse me-2"></i>Stock</a>
            </li>
            <?php endif; ?>  
            <?php if (Permisos::tienePermiso('Crear combo', $_SESSION['usuario']['id']) ): ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/combos"><i class="fas fa-gift me-2"></i>Combos</a>
            </li>
            <?php endif; ?> 
            <?php if (Permisos::tienePermiso('Crear oferta semanal', $_SESSION['usuario']['id']) ): ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/ofertaSemana"><i class="fas fa-percent me-2"></i>Oferta de la semana</a>
            </li>
            <?php endif; ?>  
            <?php if (Permisos::tienePermiso('Gestionar ventas', $_SESSION['usuario']['id']) ): ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="/<?php echo CARPETA_PROYECTO ?>/ventas"><i class="fas fa-percent me-2"></i>Ventas</a>
            </li>
            <?php endif; ?>  
            <li class="nav-item">
                <a class="nav-link text-white" class="col-6 btn btn-custom mb-3 mx-auto" href="/<?php echo CARPETA_PROYECTO ?>/home">Volver a Hardtech</a>
            </li>
        </ul>
    </nav>