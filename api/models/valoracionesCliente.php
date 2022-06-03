<?php
/*
*	Clase para manejar la tabla Valoraciones de la base de datos.
*   Es clase hija de Validator.
*/
class ValoracionesCliente extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null; //Id de la valoración
    private $comentario = null; //comentario 
    private $fk_id_cliente = null; //id del cliente llave foranea
    private $fk_id_producto = null; //id del proyecto llave foranea
    private $fk_id_valoracion = null; //id de valoracion llave foranea

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
            $this->comentario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdCliente($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdProducto($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdValoracion($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_valoracion = $value;
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
        return $this->fk_id_cliente;
    }

    public function getIdProducto()
    {
        return $this->fk_id_producto;
    }

    public function getIdValoracion()
    {
        return $this->fk_id_valoracion;
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
        $params = array($value);
        return Database::getRow($sql, $params);
    }
    //Buscar valoración de x producto de x cliente
    //Buscar valoración
    public function buscarValoracionCP($prod, $cli)
    {
        $sql = 'SELECT vcl.id_valoracionescli,vcl.comentario,vcl.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,prd.nombre_producto,
                val.valoraciones 
                FROM valoraciones_clientes as vcl 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
                INNER JOIN valoraciones	AS val ON vcl.fk_id_valoraciones = val.id_valoraciones 
                WHERE vcl.fk_id_productos = ? and WHERE vcl.fk_cliente = ?
                ORDER BY vcl.id_valoracionescli';
        $params = array("$prod%", "$cli");
        return Database::getRows($sql, $params);
    }
    //Crear una valoracion del cliente
    public function crearValoracionCli($com, $cli, $prd, $val)
    {
        $sql = 'INSERT INTO valoraciones_clientes(comentario, fk_id_cliente, fk_id_productos, fk_id_valoraciones)
        VALUES(?, ?, ?, ?)';
        $params = array($com, $cli, $prd, $val);
        return Database::executeRow($sql, $params);
    }
    //Eliminar valoracion del cliente
    public function eliminarValoracionCli()
    {
        $sql = 'DELETE FROM valoraciones_clientes
                WHERE id_valoracionescli = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
    //Obtener las valoraciones con limite
    public function obtenerValoracionesCL($limit)
    {
        $sql = 'SELECT vcl.id_valoracionescli, vcl.comentario, clt.usuario, prd.nombre_producto, vcl.fk_id_valoraciones 
                FROM valoraciones_clientes AS vcl
                INNER JOIN clientes AS clt ON vcl.fk_id_cliente = clt.id_cliente 
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto 
                WHERE vcl.id_valoracionescli
                NOT IN (SELECT id_valoracionescli FROM valoraciones_clientes ORDER BY id_valoracionescli LIMIT ?) ORDER BY vcl.id_valoracionescli limit 5';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }

    //Buscar valoración generalizada
    public function buscarValoracionG($value)
    {
        $sql = 'SELECT vcl.id_valoracionescli, vcl.comentario, clt.usuario, prd.nombre_producto, vcl.fk_id_valoraciones 
                FROM valoraciones_clientes AS vcl
                INNER JOIN clientes AS clt ON vcl.fk_id_cliente = clt.id_cliente 
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto 
                WHERE vcl.comentario ILIKE ? OR clt.usuario ILIKE ? OR prd.nombre_producto ILIKE ? 
            ORDER BY vcl.id_valoracionescli';
        $params = array("%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    //Obtener las valoraciones con limite pero manteniendo las anteriores
    public function mostrarValoracionesLimit($limit)
    {
        $sql = 'SELECT vcl.id_valoracionescli, vcl.comentario, clt.usuario, prd.nombre_producto, vcl.fk_id_valoraciones 
                FROM valoraciones_clientes AS vcl
                INNER JOIN clientes AS clt ON vcl.fk_id_cliente = clt.id_cliente 
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
                WHERE id_producto = ?
                ORDER BY vcl.id_valoracionescli DESC limit ?';
        $params = array($_SESSION['id_producto'], $limit);
        return Database::getRows($sql, $params);
    }
    //Comprobar que el cliente halla comprado el producto almenos una vez
    public function comprobarCompraCl()
    {
        $sql = 'SELECT dte.fk_id_producto, cl.id_cliente FROM detallepedidos_establecidos as dte
                INNER JOIN pedidos_establecidos AS pes ON dte.fk_id_pedidos_establecidos = pes.id_pedidos_establecidos
                INNER JOIN clientes AS cl ON pes.fk_id_cliente = cl.id_cliente
                WHERE dte.fk_id_producto = ? and cl.id_cliente = ?  and pes.fk_id_estado = 2';
        $params = array($_SESSION['id_producto'], $_SESSION['id_cliente']);
        return Database::getRows($sql, $params);
    }
    //Crear un comentario
    public function crearComentarioCl() 
    {
        $sql = 'INSERT INTO valoraciones_clientes(comentario,fk_id_cliente,fk_id_productos,fk_id_valoraciones)
            VALUES(?,?,?,?)';
        $params = array($this->comentario,$_SESSION['id_cliente'],$_SESSION['id_producto'],$this->fk_id_valoracion);
        return Database::executeRow($sql, $params);
    }
}
