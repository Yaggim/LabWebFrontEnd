<?php
    /**
     * Esta clase ofrece métodos estáticos
     * para conextarse a una BBDD.
     * 
     * 
     */
    class Conexion
    {
        private $servername  = "localhost";
        private $username    = "root"     ;
        private $password    = ""         ;
        private $db          = 'hardtech';
        private $pdo                      ;
        private static $conn = null       ;
        


        //constuctor
        public function __construct(){
            
            try{
                $this->pdo = new PDO("mysql:host=".$this->servername.";dbname=".$this->db.";charset=utf8", $this->username, $this->password);

                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Conexion a ".$this->db." OK!!!!";

            }catch(PDOException $e){
                die("Error de conexión: ". $e->getMessage());
            }
        }


        //estático para obtener conexión
        public static function getConn(){
            if(self::$conn == null){
                self::$conn = new Conexion();
            }

            return self::$conn->pdo;
        }


        //estático para cerrar conexión
        public static function closeConn(){
            if(self::$conn != null){
               self::$conn->pdo = null;
               self::$conn      = null; 
            }
        }
    }
?>