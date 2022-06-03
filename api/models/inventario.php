<?php
/*
*   Clase para manejar la tabla productos de la base de datos.
*   Es clase hija de Validator.
*/
class Inventario extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $cantidada = null;
    private $cantidadn = null;
    private $modificado = null;
    private $fecha = null;
    private $fk_id_admin = null;
    private $fk_id_producto = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidada($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cantidada = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidadn($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cantidadn = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setModificado($value)
    {
        if ($this->validateBoolean($value)) {
            $this->modificado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setFecha($value)
    {
        if ($this->validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdAdmin($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_admin = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdProd($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getCantidadAnt()
    {
        return $this->cantidada;
    }

    public function getCantidadN()
    {
        return $this->cantidadn;
    }

    public function getModificado()
    {
        return $this->modificado;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getIdAdmin()
    {
        return $this->fk_id_admin;
    }

    public function getIdProd()
    {
        return $this->fk_id_producto;
    }

     /*
    *   Métodos para gestionar la tabla de inventario
    */
    //Obtener las valoraciones con limite
    public function obtenerInventarioLt($limit){
        $sql = 'SELECT inv.id_inventario, inv.cantidada, inv.cantidadn, inv.modificado, inv.fecha,  inv.fk_id_producto, adm.nombre_admin, adm.apellido_admin, prd.nombre_producto
        FROM inventario AS inv
        INNER JOIN admins AS adm ON inv.fk_id_admin = adm.id_admin
        INNER JOIN productos AS prd ON inv.fk_id_producto = prd.id_producto
        WHERE inv.id_inventario 
        NOT IN (SELECT id_inventario FROM inventario ORDER BY id_inventario LIMIT ?) ORDER BY inv.id_inventario DESC LIMIT 5';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }
    //Buscar un inventario en especifico
    public function buscarInventario(){
        $sql = 'SELECT inv.id_inventario, inv.cantidada, inv.cantidadn, inv.modificado, inv.fecha,  inv.fk_id_producto, adm.nombre_admin, adm.apellido_admin, prd.nombre_producto
        FROM inventario AS inv
        INNER JOIN admins AS adm ON inv.fk_id_admin = adm.id_admin
        INNER JOIN productos AS prd ON inv.fk_id_producto = prd.id_producto
        WHERE inv.id_inventario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    //Eliminar un inventario
    public function eliminarInventario(){
        $sql = 'DELETE FROM inventario 
                WHERE id_inventario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
    //Añadir un inventario
    public function crearInventario(){
        $sql = 'INSERT  INTO inventario(cantidada,cantidadn,modificado,fk_id_admin,fk_id_producto) 
                VALUES(?,?,false,?,?)';
        $params = array($this->cantidada, $this->cantidadn, $_SESSION['id_usuario'], $this->fk_id_producto);
        return Database::executeRow($sql, $params);
    }
    //Buscar la imagen de un producto
    public function imgProducto($idp){
        $sql = 'SELECT imagen_producto,nombre_producto,descripcion,cantidad FROM productos WHERE id_producto = ?';
        $params = array($idp);
        return Database::getRow($sql, $params);
    }
    //Actualizar inventario
    public function actualizarInv(){
        $sql = 'UPDATE inventario SET cantidadn = ?, modificado = true, fk_id_admin= ?
                WHERE id_inventario = ?';
        $params = array($this->cantidadn, $_SESSION['id_usuario'], $this->id);
        return Database::executeRow($sql, $params);
    }
    //Actualizar la cantidad del producto
    public function actualizarCanPrd(){
        $sql = 'UPDATE productos SET cantidad = ?
                WHERE id_producto = ?';
        $params = array($this->cantidada, $this->fk_id_producto);
        return Database::executeRow($sql, $params);
    }
    //Metodo para buscar
    public function buscarInv($value){
        $sql = 'SELECT inv.id_inventario, inv.cantidada, inv.cantidadn, inv.modificado, inv.fecha,  inv.fk_id_producto, adm.nombre_admin, adm.apellido_admin, prd.nombre_producto
                FROM inventario AS inv
                INNER JOIN admins AS adm ON inv.fk_id_admin = adm.id_admin
                INNER JOIN productos AS prd ON inv.fk_id_producto = prd.id_producto
                WHERE cast(inv.cantidada as varchar) ILIKE ? OR cast(inv.cantidadn as varchar) ILIKE ? OR cast(inv.fecha as varchar) ILIKE ? OR adm.nombre_admin ILIKE ? OR adm.apellido_admin ILIKE ? OR prd.nombre_producto ILIKE ?
                 ORDER BY inv.id_inventario DESC';
        $params = array("%$value%","%$value%","%$value%","%$value%","%$value%","%$value%");
        return Database::getRows($sql, $params);
    }
}