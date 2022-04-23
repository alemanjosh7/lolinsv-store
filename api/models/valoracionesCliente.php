<?php
/*
*	Clase para manejar la tabla Valoraciones de la base de datos.
*   Es clase hija de Validator.
*/
class ValoracionesCliente extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;//Id de la valoración
    private $comentario = null;//comentario 
    private $id_cliente = null;//id del cliente llave foranea
    private $id_producto = null;//id del proyecto llave foranea
    private $id_valoracion = null;//id de valoracion llave foranea

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

    public function setComentario($value)
    {
        if ($this->validateString($value, 1, 500)) {
            $this->id_valoracion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdCliente($value){
        if ($this->validateNaturalNumber($value)) {
            $this->id_cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdProducto($value){
        if ($this->validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdValoracion($value){
        if ($this->validateNaturalNumber($value)) {
            $this->id_valoracion = $value;
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

    public function getComentario()
    {
        return $this->comentario;
    }

    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    public function getIdProducto()
    {
        return $this->id_producto;
    }

    public function getIdValoracion()
    {
        return $this->id_valoracion;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar valoraciones de x cliente
    public function buscarValoracionesCli($value)
    {
        $sql = 'SELECT vcl.id_valoracionescli,vcl.comentario,vcl.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,prd.nombre_producto,
                val.valoraciones 
                FROM valoraciones_clientes as vcl 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
                INNER JOIN valoraciones	AS val ON vcl.fk_id_valoraciones = val.id_valoraciones 
                WHERE vcl.fk_id_cliente = ?
                ORDER BY vcl.id_valoracionescli';
        $params = $value;
        return Database::getRows($sql, $params);
    }
    //Buscar valoraciones de x producto
    public function buscarValoracionesProd($value)
    {
        $sql = 'SELECT vcl.id_valoracionescli,vcl.comentario,vcl.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,prd.nombre_producto,
                val.valoraciones 
                FROM valoraciones_clientes as vcl 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
                INNER JOIN valoraciones	AS val ON vcl.fk_id_valoraciones = val.id_valoraciones 
                WHERE vcl.fk_id_producto = ?
                ORDER BY vcl.id_valoracionescli';
        $params = $value;
        return Database::getRows($sql, $params);
    }
    //Buscar valoración
    public function buscarValoracion($value)
    {
        $sql = 'SELECT vcl.id_valoracionescli,vcl.comentario,vcl.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,prd.nombre_producto,
                val.valoraciones 
                FROM valoraciones_clientes as vcl 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
                INNER JOIN valoraciones	AS val ON vcl.fk_id_valoraciones = val.id_valoraciones 
                WHERE vcl.id_valoracionescli = ?
                ORDER BY vcl.id_valoracionescli';
        $params = $value;
        return Database::getRow($sql, $params);
    }
    //Buscar valoración de x producto de x cliente
    //Buscar valoración
    public function buscarValoracionCP($prod,$cli)
    {
        $sql = 'SELECT vcl.id_valoracionescli,vcl.comentario,vcl.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,prd.nombre_producto,
                val.valoraciones 
                FROM valoraciones_clientes as vcl 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
                INNER JOIN valoraciones	AS val ON vcl.fk_id_valoraciones = val.id_valoraciones 
                WHERE vcl.fk_id_productos = ? and WHERE vcl.fk_cliente = ?
                ORDER BY vcl.id_valoracionescli';
        $params = array($prod%, $cli);
        return Database::getRows($sql, $params);
    }
    //Crear una valoracion del cliente
    public crearValoracionCli($com,$cli,$prd,$val){
        $sql = 'INSERT INTO valoraciones_clientes(comentario, fk_id_cliente, fk_id_productos, fk_id_valoraciones)
        VALUES(?, ?, ?, ?)';
        $params = array($com, $cli, $prd, $val);
        return Database::executeRow($sql, $params);
    }
    //Eliminar valoracion del cliente
    public eliminarValoracionCli(){
        $sql = 'DELETE FROM valoraciones_clientes
                WHERE id_valoracionescli = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
