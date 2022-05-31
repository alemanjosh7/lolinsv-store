<?php
/*
*	Clase para manejar la tabla categorias de la base de datos.
*   Es clase hija de Validator.
*/
class detallePedidos_establecidos extends Validator
{
   // Declaración de atributos (propiedades).
    private $id_detalle_pedidos = null;//Id de detalle de pedidos
    private $cantidad_detallep = null;//cantidad
    private $subtotal_detallep = null;//subtotal
    private $id_producto = null;//id del producto llave foranea
    private $id_pedidos_establecidos = null;//id de pedidos establecidos llave foranea

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
     public function setId_detalle_pedidos($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_detalle_pedidos = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidad_detallep($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cantidad_detallep = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setSubtotal_detallep($value){
        if ($this->validateMoney($value)) {
            $this->subtotal_detallep = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_producto($value){
        if ($this->validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_pedidos_establecidos($value){
        if ($this->validateNaturalNumber($value)) {
            $this->id_pedidos_establecidos = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_detalle_pedidos()
    {
        return $this->id_detalle_pedidos;
    }

    public function getCantidad_detallep()
    {
        return $this->cantidad_detallep;
    }

    public function getSubtotal_detallep()
    {
        return $this->subtotal_detallep;
    }

    public function getId_producto()
    {
        return $this->id_producto;
    }

    public function getId_pedidos_establecidos()
    {
        return $this->id_pedidos_establecidos;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    //buscar columna de detalle de pedidos establecidos 
    public function buscarColDPE($value)
    {
        $sql = '';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    //crear columna de detalle de pedidos establecidos 

    public function crearColDPE()
    {
        $sql = 'INSERT INTO detallepedidos_establecidos(cantidad_detallep, subtotal_detallep)
                VALUES(?, ?)';
        $params = array($this->cantidad_detallep, $this->subtotal_detallep);
        return Database::executeRow($sql, $params);
    }

    //mostrar todas las columnas de detalle de pedidos establecidos 

    public function todoDPE()
    {
        $sql = 'SELECT id_detalle_pedidos, cantidad_detallep, subtotal_detallep, fk_id_producto, fk_id_pedidos_establecidos
                FROM detallepedidos_establecidos
                ORDER BY nombre_categoria';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //obtener columna de detalle de pedidos establecidos por id

    public function obtenerDPE()
    {
        $sql = 'SELECT id_detalle_pedidos, cantidad_detallep, subtotal_detallep, fk_id_producto, fk_id_pedidos_establecidos
                FROM detallepedidos_establecidos
                WHERE id_detalle_pedidos = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }


    //eliminar  detalle de pedidos establecidos

    public function eliminarColDPE()
    {
        $sql = 'DELETE FROM detallepedidos_establecidos
                WHERE id_detalle_pedidos = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //obtener cantidad y subtotal de detalle de pedidos establecidos por id

    public function obtenerDPEesp()
    {
             $sql = 'SELECT id_detalle_pedidos, cantidad_detallep, subtotal_detallep
                FROM detallepedidos_establecidos 
                WHERE id_detalle_pedidos = ?
                ORDER BY id_detalle_pedidos';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}