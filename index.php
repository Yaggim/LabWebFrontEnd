<?php require_once 'config/config.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech</title>
    <?php require('components/head.php') ?>
</head>

<body>
    <?php require('components/header.php'); ?>

	<main>
	<!---------------------Inicio Carrusel----------------------------------------------->
<div class="container-fluid carrusel">
		<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <img src="images/banner_car01.jpg" class="d-block w-100" alt="...">
		    </div>
		    <div class="carousel-item">
		      <img src="images/banner_car02.jpg" class="d-block w-100" alt="...">
		    </div>
		    <div class="carousel-item">
		      <img src="images/banner_car03.jpg" class="d-block w-100" alt="...">
		    </div>
		    <div class="carousel-item">
		      <img src="images/banner_car04.jpg" class="d-block w-100" alt="...">
		    </div>
		  </div>
		  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="visually-hidden">Previous</span>
		  </button>
		  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="visually-hidden">Next</span>
		  </button>
		</div>
</div>
<!-----------------------------FIN Carrusel-------------------------------------------->

<!----------------------------Contactos------------------------------------------------>
<div>
	<div class="container text-center g-info">
  		<div class="row">
    		<div class="col">
      			<div class="card card-fondo" style="width: 18rem;">
  						<img src="" class="card-img-top" alt="">
 						 	<div class="card-body">
    						<p class="card-text">
    							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
  								<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
									</svg>
									<div class="texto-card">Horarios:</div>
									<div>Lunes a viernes 9:00hs a 19:00hs</div>
									<div>Sábados 9:00hs a 12:00hs</div>
    						</p>
  						</div>
						</div>
    		</div>
    		<div class="col">
      			<div class="card card-fondo" style="width: 18rem;">
  						<img src="" class="card-img-top" alt="">
 						 	<div class="card-body">
    						<p class="card-text">
   							 	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
  								<path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
  						  </svg> 
  							<div class="texto-card">Venta telefónica:</div>
								<div>011- 2222-2222-2222/2589</div><br>


    						</p>
  						</div>
						</div>
    		</div>
    		<div class="col">
      			<div class="card card-fondo" style="width: 18rem;">
  						<img src="" class="card-img-top" alt="">
 						 	<div class="card-body">
    						<p class="card-text">
    							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
 									 <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
										</svg><div class="texto-card"> Escribinos a:</div>
													<div>consultas@hardtech.com.ar</div><br>
    						</p>
  						</div>
						</div>
    		</div>
  		</div>
	</div>	
</div>
<!------------------------------Fin Contactos------------------------------------------>


<!----------------------------Grid para los cards-------------------------------------->
	<div class="container-fluid g-info">
  	<div class="row">
    	<div class="col-md-3 col-lg-3 col-sm-6 col-xm-6">
      	<div class="card">
  				<img src="images/card01.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="#" class="btn btn-primary">Armá tu PC</a></h5>
				</div>
			</div>
  	</div>
    <div class="col-md-3 col-lg-3 col-sm-6 col-xm-6">
       	<div class="card" >
  				<img src="images/card02.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="#" class="btn btn-primary">PC Oficina</a></h5>
				</div>
			</div>
  	</div>    
    <div class="col-md-3 col-lg-3 col-sm-6 col-xm-6">
      	<div class="card">
  				<img src="images/card3.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="#" class="btn btn-primary">PC Gamer</a></h5>
				</div>
			</div>
  	</div>
    <div class="col-md-3 col-lg-3 col-sm-6 col-xm-6">
  <div class="card" >
  				<img src="images/card04.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="#" class="btn btn-primary">PC Gamer Extreme</a></h5>
				</div>
			</div>
  	</div>
   </div>
  </div>
</div>

<!----------------------------Fin Grid para los cards---------------------------------->
<
<!-------------------------------Destacados-------------------------------------------->
<!-----------------------Título--------------------->
<div id="destacados">
	<h2>Productos Destacados</h2>
</div>

<!-----------------------Carrusel de destacados--------------------------------------->


<div id="carouselExample" class="carousel slide productos" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/sw74epduIVIGGURcCgih/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Detalle del producto</h5>
                            <a href="#" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/BmfhqzK84PSpXbjn6a3s/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Detalle del producto</h5>
                           <a href="#" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/fVYiapxtEJpnvCzZ6BNu/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Detalle del producto</h5>
                           <a href="#" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/fVYiapxtEJpnvCzZ6BNu/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Detalle del producto</h5>
                            <a href="#" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/gegAiCMDQgyTe7vzvdc4/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Detalle del producto</h5>
                            <a href="#" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/QpedN0h0axYamiITGwes/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Detalle del producto</h5>
                            <a href="#" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<!------------------------------Recibí un email---------------------------------------->

<div class="container-fluid email">
	<div class="row">
			<div class="col-md-12 col-lg-12">
					<div class="nv-ht">
						<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-envelope-check-fill" viewBox="0 0 16 16">
  					<path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 4.697v4.974A4.5 4.5 0 0 0 12.5 8a4.5 4.5 0 0 0-1.965.45l-.338-.207z"/>
  					<path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686"/>
					</svg> Recibí las mejores ofertas por correo!
						<input type="email" name=""  placeholder="Tu Email">
						<button class="btn btn-outline-success">Suscribirme</button>
					</div>
			</div>

	</div>
</div>
<!---------------------------------Fin recibi email----------------------------------->	
	</main>

    <?php require('components/footer.php'); ?>
</body>
</html>