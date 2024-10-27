<?php
	require_once(__DIR__."../Crud.php");
	/**
	 * Subclases para implementar
	 * métodos específicos
	 * 
	 */


	class CategoriaBBDD extends Crud {
	
		public function __construct(){
			parent::__construct("categorias");
		}
	}




	class ComboBBDD extends Crud {

		public function __construct(){
			parent::__construct("combos");
		}


		//retorna combos habilitados
		public function getHabilitados(){
			$stmt = $this->conexion->prepare("SELECT * FROM {$this->tabla} WHERE habilitado != 0");
        	$stmt->execute();

        	//retorna TODOS los registros
        	return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

	}





	class EnvioBBDD extends Crud {

		public function __construct(){
			parent::__construct("envios");
		}

	}





	class EstadosVentaBBDD extends Crud {
		
		public function __construct(){
			parent::__construct("estados_ventas");
		}		
	}





	class ImagenesComboBBDD extends Crud {

		public function __construct(){
			parent::__construct("imagenes_combos");
		}
	}





	class ImagenesProductoBBDD extends Crud {

		public function __construct(){
			parent::__construct("imagenes_productos");
		}
	}





	class MarcaBBDD extends Crud {

		public function __construct(){
			parent::__construct("marcas");
		}
	}





	class ModeloBBDD extends Crud {

		public function __construct(){
			parent::__construct("modelos");
		}
	}





	class MovimientoStockBBDD extends Crud {

		public function __construct(){
			parent::__construct("movimientos_stock");
		}

	}
        




     
    class MovimientoStockTipoBBDD extends Crud {

    	public function __construct(){
			parent::__construct("movimientos_stock_tipo");
		}
    }






	class OfertaSemanaBBDD extends Crud {

		public function __construct(){
			parent::__construct("ofertas_semana");
		}
	}






	class PermisoBBDD extends Crud {
		
		public function __construct(){
			parent::__construct("permisos");
		}
	}






	class PersonaBBDD extends Crud {

		public function __construct(){
			parent::__construct("personas");
		}
	}






	class ProductoBBDD extends Crud {

		public function __construct(){
			parent::__construct("productos");
		}
	}






	class ProductoComboBBDD extends Crud {

		public function __construct(){
			parent::__construct("productos_combo");
		}
	}






	class RolBBDD extends Crud {

		public function __construct(){
			parent::__construct("roles");
		}
	}





	class RolPermisoBBDD extends Crud {

		public function __construct(){
			parent::__construct("roles_permisos");
		}
	}




	class RolUsuarioBBDD extends Crud {

		public function __construct(){
			parent::__construct("roles_usuarios");
		}
	} 






	class UsuarioBBDD extends Crud {

		public function __construct(){
			parent::__construct("usuarios");
		}
	}




	class VentaCabeceraBBDD extends Crud {

		public function __construct(){
			parent::__construct("ventas_cabecera");
		}
	}





	class VentaDetalleBBDD extends Crud {

		public function __construct(){
			parent::__construct("venta_detalle");
		}
	}
?>