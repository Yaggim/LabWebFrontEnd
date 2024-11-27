<?php require_once 'config/config.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech</title>
    <?php require('app/views/components/head.php') ?>
</head>

<body>
    <?php require('app/views/components/header.php'); ?>

	<main>
	<!---------------------Inicio Carrusel----------------------------------------------->
<div class="container-fluid carrusel">
		<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <img src="public/images/banner_car01.jpg" class="d-block w-100" alt="...">
		    </div>
		    <div class="carousel-item">
		      <img src="public/images/banner_car02.jpg" class="d-block w-100" alt="...">
		    </div>
		    <div class="carousel-item">
		      <img src="public/images/banner_car03.jpg" class="d-block w-100" alt="...">
		    </div>
		    <div class="carousel-item">
		      <img src="public/images/banner_car04.jpg" class="d-block w-100" alt="...">
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

	<div class="container justify-content-center g-info">
  		<div class="row">
    		<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
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
    		<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
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
    		<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
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
    	<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
      	<div class="card">
  				<img src="public/images/card01.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="arma-tu-pc" class="btn btn-primary">Armá tu PC</a></h5>
				</div>
			</div>
  	</div>
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
       	<div class="card">
  				<img src="public/images/card02.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="arma-tu-pc" class="btn btn-primary">PC Oficina</a></h5>
				</div>
			</div>
  	</div>    
    <div class="col-lg-3 col-md-6 col-sm-12 mxb-3">
      	<div class="card">
  				<img src="public/images/card3.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="arma-tu-pc" class="btn btn-primary">PC Gamer</a></h5>
				</div>
			</div>
  	</div>
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
  		<div class="card">
  				<img src="public/images/card04.jpg" class="card-img-top card-img-top" alt="...">
  			<div class="card-body">
    			<h5 class="card-title"> <a href="arma-tu-pc" class="btn btn-primary">PC Gamer Extreme</a></h5>
				</div>
			</div>
  		</div>
   	</div>
  </div>
</div>


<!----------------------------Fin Grid para los cards---------------------------------->

<!-------------------------------Destacados-------------------------------------------->
<!-----------------------Título--------------------->
<div class="d-flex justify-content-center flex-wrap">
	<h2>Encontrá tu producto</h2>
</div>

<!-----------------------Carrusel de destacados--------------------------------------->


<div id="carouselExample" class="carousel slide productos" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/sw74epduIVIGGURcCgih/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
						<a href="productos" class="btn btn-primary">Ver</a>
                         
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/BmfhqzK84PSpXbjn6a3s/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
						<a href="productos" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/fVYiapxtEJpnvCzZ6BNu/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
						<a href="productos" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/fVYiapxtEJpnvCzZ6BNu/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
						<a href="productos" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/gegAiCMDQgyTe7vzvdc4/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
							<a href="productos" class="btn btn-primary">Ver</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="https://s3-sa-east-1.amazonaws.com/saasargentina/QpedN0h0axYamiITGwes/imagen" class="card-img-top" alt="...">
                        <div class="card-body">
							<a href="productos" class="btn btn-primary">Ver</a>
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

</main>

    <?php require('app/views/components/footer.php'); ?>
</body>
</html>