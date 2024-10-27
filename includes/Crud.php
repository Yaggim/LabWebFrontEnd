<?php
require_once(__DIR__."../conexion.php");

/**
 * Esta clase expone CRUD genéricos
 * 
 */



class Crud {
    protected $conexion;
    protected $tabla;


    //Constructor
    public function __construct($nombre_tabla) {
        $this->conexion = Conexion::getConn();
        $this->tabla    = $nombre_tabla;
    }


    //Obtener todos los registros
    public function getAll() {
        $stmt = $this->conexion->prepare("SELECT * FROM {$this->tabla}");
        $stmt->execute();

        //retorna TODOS los registros
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //Obtener registro por id y el nomnbre del id
    public function getById($id, $PK_nombre) {
        $stmt = $this->conexion->prepare("SELECT * FROM {$this->tabla} WHERE $PK_nombre = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        //retorna el registro si existe
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    //Insertar un registro 
    //recibe un array asociativo nombre_del_campo => valor
    public function create($array_asoc) {
        $columnas = implode(", ", array_keys($array_asoc));
        $values = ":" . implode(", :", array_keys($array_asoc));
        $sql = "INSERT INTO {$this->tabla} ($columnas) VALUES ($values)";
        $stmt = $this->conexion->prepare($sql);

        foreach ($array_asoc as $clave => &$valor) {
            $stmt->bindParam(":$clave", $valor);
        }

        $stmt->execute();

        //retorna filas afectadas
        return $stmt->rowCount();
    }


    //Borra por id y nombre del campo id
    public function deleteById($id, $id_nombre) {
        $stmt = $this->conexion->prepare("DELETE FROM {$this->tabla} WHERE $id_nombre = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        //retorna filas afectadas
        return $stmt->rowCount();
    }
}
?>
