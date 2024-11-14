<?php

require_once __DIR__ .'/../config/config.php';

?>

<footer class="bg-dark text-white pt-4">
    <div class="bg-dark container-fluid">
        <div class="row justify-content-md-center ">
                <div class="col-lg-auto col-md-12 mx-auto footer-info">
                    <h5>Atención al cliente</h5>
                    <ul class="list-unstyled">
                        <li>Lunes a viernes de 9 a 18hs.</li>
                        <li>
                            <i class="bi bi-whatsapp"> </i>
                            <a href="https://api.whatsapp.com/send/?phone=5491123456789" target="_blank" class="fw-medium"> +54 11 2345-6789 </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-auto col-md-12 mx-auto footer-info enlaces-footer">
                    <h5>Enlaces</h5>
                    <ul class="list-unstyled">
                        <li>➤ <a href="/<?php echo CARPETA_PROYECTO ?>/arma-tu-pc" class="arma-pc-footer text-white text-decoration-none">Armá tu PC</a></li>
                        <li><a href="/<?php echo CARPETA_PROYECTO ?>/home" class="text-white text-decoration-none">Inicio</a></li>
                        <li><a href="/<?php echo CARPETA_PROYECTO ?>/productos" class="text-white text-decoration-none">Productos</a></li>
                        <li><a href="JavaScript:Void(0)" class="text-white text-decoration-none">Novedades</a></li>
                        <?php if ($usuario->isLoggedIn()): ?>
                            <li><a href="/<?php echo CARPETA_PROYECTO ?>/perfil" class="text-white text-decoration-none">Mi Perfil</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-lg-auto col-md-12 mx-auto footer-info">
                    <h5 id="contacto">Contacto</h5>
                    <ul class="list-unstyled">
                        <li>
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:consultas@hardtech.com.ar" class="fw-medium">consultas@hardtech.com.ar</a>
                    </li>
                        <li><i class="bi bi-telephone"></i> +54 3456 7890</li>
                        <li><i class="bi bi-geo-alt"></i> Somewhere, Nowhere</li>
                    </ul>
                </div>

            <!-- Línea divisoria -->
                <div>
                    <hr class="my-4">
                </div>
            <!-- Sección de derechos reservados -->
            <div class="text-center py-3 lead fs-6">
                <p>&copy; 2024 HardTech. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</footer>
