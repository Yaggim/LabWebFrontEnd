<?php
    /**
     * Esta clase ofrece métodos estáticos
     * para conextarse a una BBDD.
     */
    class Conexion
    {
        private $servername;
        private $username;
        private $password;
        private $db;
        private $pdo;
        private static $conn = null;

        //constuctor
        public function __construct(){

            $this->servername = getenv('DB_HOST');
            $this->username = getenv('DB_USER');
            $this->password = getenv('DB_PASS');
            $this->db = getenv('DB_NAME');
            
            try{
                $this->pdo = new PDO("mysql:host=".$this->servername.";dbname=".$this->db.";charset=utf8", $this->username, $this->password);

                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

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