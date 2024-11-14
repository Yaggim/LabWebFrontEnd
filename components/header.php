<header>
    <!-- Primera barra: Logo, Barra de búsqueda, Iconos -->
    <nav class="barra_main">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center">

                <!-- Icono de hamburguesa -->
                <div class="navbar navbar-dark col-auto d-lg-none">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><span class="navbar-toggler-icon"></span></button>
                </div>

                <!-- Contenido del ícono de hamburguesa -->
                <div class="offcanvas offcanvas-start " tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" data-bs-theme="dark">
                    <div class="offcanvas-header p-4 fs-5">
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body pt-3 pb-0" >
                        <ul class="navbar-nav">
                            <li class="nav-item arma-pc-offcanvas">
                                <a class="nav-link py-3 px-5" aria-current="page" href="/<?php echo CARPETA_PROYECTO ?>/arma-tu-pc">Armá tu PC</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-3 px-5" aria-current="page" href="/<?php echo CARPETA_PROYECTO ?>/productos">Productos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-3 px-5" href="/<?php echo CARPETA_PROYECTO ?>/productos">Ofertas</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link py-3 px-5 dropdown-toggle" href="JavaScript:Void(0)" id="navbarDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#cat-burger" aria-expanded="false">
                                    Categorías
                                </a>
                                <ul class="list-unstyled collapse" aria-labelledby="navbarDropdown" id="cat-burger">
                                    <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/productos">Combos</a></li>
                                    <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=monitores">Monitores</a></li>
                                    <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=procesadores">Procesadores</a></li>
                                    <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=memorias">Memorias</a></li>
                                    <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=discos">Discos</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-3 px-5" id="contactLink" href="#contacto" data-bs-dismiss="offcanvas">Contacto</a>
                            </li>
                        </ul>
                    </div>
                    <div class="d-lg-none align-self-center p-4 mb-2">
                        <a href=""><i class="bi bi-tiktok px-3 fs-2"></i></a>
                        <a href=""><i class="bi bi-whatsapp px-3 fs-2"></i></a>
                        <a href=""><i class="bi bi-telegram px-3 fs-2"></i></a>
                        <a href=""><i class="bi bi-facebook px-3 fs-2"></i></a>
                    </div>
                </div>

                <!-- Logo (SVG) -->
                <div class="col-auto">
                    <a class="navbar-brand" href="/<?php echo CARPETA_PROYECTO ?>/home">
                        <svg version="1.0" class="svg-logo" viewBox="40 40 240 80" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" color-interpolation-filters="sRGB" preserveAspectRatio="xMidYMid meet"> <rect x="62" y="60.84" width="36.6" height="39" fill="#fefefd" fill-opacity="1"></rect>  <g fill="#5273FF" transform="translate(50,50)"><g transform="translate(0,0)"><g><rect fill="#5273FF" fill-opacity="0" stroke-width="2" x="0" y="0" width="60.00000000000001" height="59.80086312579112"></rect> <svg x="0" y="0" width="60.00000000000001" height="59.80086312579112" filtersec="colorsb9545054840" style="overflow: visible;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120.40000915527344 120"><path fill="#7ac94c" d="M0 0v98.22h24.16V21.78h24.16v25.74h23.36V21.78h24.56V0H0z"></path><path fill="#5273FF" d="M120.4 120V21.78H96.24v76.44H72.08V72.47H48.71v25.75H24.16V120h96.24z"></path></svg></svg> </g></g> <g transform="translate(65,16.435431480407715)"><g data-gra="path-name" fill-rule=""><g transform="scale(1)"><g><path d="M8.91-4.93L9.86-4.93Q11.87-4.93 14.42-5.69L14.42-5.69 14.42-7.97 8.91-7.97Q8.35-7.97 7.97-7.59 7.59-7.21 7.59-6.64L7.59-6.64 7.59-6.26Q7.59-5.69 7.97-5.31 8.35-4.93 8.91-4.93L8.91-4.93ZM2.85-14.98L2.85-19.73Q9.79-20.48 15.36-20.48L15.36-20.48Q17.94-20.48 19.5-18.93 21.05-17.37 21.05-14.79L21.05-14.79 21.05 0 15.17 0 14.79-1.9Q13.01-0.72 11.13-0.17 9.26 0.38 7.97 0.38L7.97 0.38 6.64 0.38Q4.06 0.38 2.5-1.18 0.95-2.73 0.95-5.31L0.95-5.31 0.95-7.02Q0.95-9.6 2.5-11.15 4.06-12.71 6.64-12.71L6.64-12.71 14.42-12.71 14.42-14.23Q14.42-14.79 14.04-15.17 13.66-15.55 13.09-15.55L13.09-15.55Q10.62-15.55 7.64-15.33 4.67-15.1 2.85-14.98L2.85-14.98ZM39.26-14.6L36.04-14.6Q33.65-14.6 31.11-13.47L31.11-13.47 31.11 0 24.47 0 24.47-20.11 30.35-20.11 30.73-17.64Q33.99-20.48 37.56-20.48L37.56-20.48 39.26-20.48 39.26-14.6ZM61.26 0L55.38 0 55.01-1.9Q53.22-0.72 51.34-0.17 49.47 0.38 48.18 0.38L48.18 0.38 46.47 0.38Q43.89 0.38 42.34-1.18 40.78-2.73 40.78-5.31L40.78-5.31 40.78-14.04Q40.78-16.88 42.39-18.49 44-20.11 46.85-20.11L46.85-20.11 54.63-20.11 54.63-26.55 61.26-26.55 61.26 0ZM48.75-5.12L50.07-5.12Q52.08-5.12 54.63-5.88L54.63-5.88 54.63-14.98 48.94-14.98Q47.42-14.98 47.42-13.47L47.42-13.47 47.42-6.45Q47.42-5.88 47.8-5.5 48.18-5.12 48.75-5.12L48.75-5.12ZM78.34-21.24L78.34 0 71.51 0 71.51-21.24 63.54-21.24 63.54-26.55 86.3-26.55 86.3-21.24 78.34-21.24ZM97.87-15.74L94.46-15.74Q92.94-15.74 92.94-14.23L92.94-14.23 92.94-12.14 99.39-12.14 99.39-14.23Q99.39-15.74 97.87-15.74L97.87-15.74ZM105.27-5.12L105.27-0.38Q97.95 0.38 91.99 0.38L91.99 0.38Q89.41 0.38 87.86-1.18 86.3-2.73 86.3-5.31L86.3-5.31 86.3-14.42Q86.3-17.26 87.91-18.87 89.53-20.48 92.37-20.48L92.37-20.48 99.96-20.48Q102.8-20.48 104.42-18.87 106.03-17.26 106.03-14.42L106.03-14.42 106.03-7.4 92.94-7.4 92.94-5.88Q92.94-5.31 93.32-4.93 93.7-4.55 94.27-4.55L94.27-4.55Q98.02-4.55 105.27-5.12L105.27-5.12ZM127.27-5.31L127.27-0.38Q120.18 0.38 114.75 0.38L114.75 0.38Q112.17 0.38 110.62-1.18 109.06-2.73 109.06-5.31L109.06-5.31 109.06-14.04Q109.06-16.88 110.67-18.49 112.29-20.11 115.13-20.11L115.13-20.11 127.27-20.11 127.27-14.98 117.22-14.98Q115.7-14.98 115.7-13.47L115.7-13.47 115.7-6.07Q115.7-5.5 116.08-5.12 116.46-4.74 117.03-4.74L117.03-4.74Q120.44-4.74 127.27-5.31L127.27-5.31ZM136.94-26.55L136.94-19.73Q140.62-20.48 143.39-20.48L143.39-20.48 145.1-20.48Q147.68-20.48 149.23-18.93 150.79-17.37 150.79-14.79L150.79-14.79 150.79 0 144.15 0 144.15-14.04Q144.15-14.6 143.77-14.98 143.39-15.36 142.82-15.36L142.82-15.36 141.5-15.36Q140.05-15.36 138.86-15.21 137.66-15.06 136.94-14.98L136.94-14.98 136.94 0 130.31 0 130.31-26.55 136.94-26.55Z" transform="translate(-0.9500000476837158, 26.549999237060547)"></path></g> </g></g>  </g></g></svg>
                    </a>
                </div>

                <!-- Barra de búsqueda -->
                <div class="col-12 col-md-4 d-none d-lg-block d-flex justify-content-center align-items-center">
                    <form name="formBusqueda" action="" method="" id="formBusqueda" role="search" class="justify-content-center">
                        <div class="form-group">
                            <div class="input-group me-3">
                                <input type="text" class="form-control search-bar" placeholder="Buscar Producto" aria-label="Search">
                                <button class="btn btn-custom-search" type="submit">
                                    <span class="input-group-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </button>
                                <!-- Resultados de búsqueda justo debajo del input -->
                                <ul id="" class="bg-light text-dark overflow-auto resultados-busqueda"></ul>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Iconos de usuario, login y carrito -->
                <div class="col-auto d-flex justify-content-end">
                    <a href="JavaScript:Void(0)" class="me-3 d-lg-none" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fa fa-search fs-5"></i>
                    </a>
                    <a href="JavaScript:Void(0)" type="button" class="me-3 me-md-3 me-lg-4 ms-sm-2 ms-md-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" id="btnFormLogin" >
                        <i class="fa fa-user fs-5"></i>
                    </a>
                    <form method="POST" class="dropdown-menu p-4 form-login" id="btnDropdownLogin" >
                        <?php if (!$usuario->isLoggedIn()): require RUTA_PROYECTO.'/components/form_login.php'; ?>
                        <?php else: require RUTA_PROYECTO.'/components/form_logout.php'; ?>
                        <?php endif; ?>
                    </form>
                    <a href="/<?php echo CARPETA_PROYECTO ?>/carrito" class="me-3 me-md-3 me-lg-4 ms-sm-2">
                        <i class="fa fa-shopping-cart fs-5"></i>
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <!-- Segunda barra: Navegación -->
    <nav class="navbar navbar-expand-md navbar-dark second-navbar d-none d-lg-block">
        <div class="" id="navbarNav">
            <ul class="navbar-nav d-flex justify-content-evenly ">
                <li class="nav-item">
                    <a class="nav-link arma-pc-nav" aria-current="page" href="/<?php echo CARPETA_PROYECTO ?>/arma-tu-pc">Armá tu PC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/<?php echo CARPETA_PROYECTO ?>/productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/<?php echo CARPETA_PROYECTO ?>/productos">Ofertas</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="JavaScrip:Void(0)" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-target="#cat-navbar" >
                        Categorías
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark " aria-labelledby="navbarDropdown" id="cat-navbar" >
                        <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/productos">Combos</a></li>
                        <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=monitores">Monitores</a></li>
                        <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=procesadores">Procesadores</a></li>
                        <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=memorias">Memorias</a></li>
                        <li><a class="dropdown-item" href="/<?php echo CARPETA_PROYECTO ?>/resultado-busqueda?query=discos">Discos</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contacto">Contacto</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Modal de búsqueda -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Buscar producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="formBusquedaModal" action="" method="" id="formBusquedaModal" role="search" class="d-flex w-100 justify-content-center"></form>
                        <div class="form-group p-4">
                            <div class="input-group me-2">
                                <input type="text" class="form-control search-bar" placeholder="Nombre del producto" aria-label="Search">
                                <button class="btn btn-custom-search" type="submit">
                                    <span class="input-group-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </button>
                                <!-- Resultados de búsqueda justo debajo del input -->
                                <ul id="" class="bg-light text-dark overflow-auto resultados-busqueda"></ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
