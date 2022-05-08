<?php
/*
*	Clase para manejar la tabla Valoraciones de la base de datos.
*   Es clase hija de Validator.
*/
class Pedidos_personalizados extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;//Id del tamaño
    private $fecha_pedidopersonal = null;//fecha 
    private $descripcion_pedidopersonal = null;//descripcion del pedido
    private $imagenejemplo_pedidopersonal = null;//imagen 
    private $descripcionlugar_entrega = null;//descripcion del lugar
    private $id_cliente = null;//id del cliente llave foranea
    private $id_tamano = null;//id del tamaño llave foranea
    private $id_estado = null;//id de estado llave foranea
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

    public function setFecha($value)
    {
        if ($this->validateDate($value)) {
            $this->fecha_pedidopersonal = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripPedido($value)
    {
        if ($this->validateString($value, 1, 500)) {
            $this->descripcion_pedidopersonal = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setImagen($file)
    {
        if ($this->validateImageFile($file, 500, 500)) {
            $this->imagenejemplo_pedidopersonal = $this->getFileName();
            return true;
        } else {
            return false;
        }
    }

    public function setDescripLugar($value)
    {
        if ($this->validateString($value, 1, 500)) {
            $this->descripcionlugar_entrega = $value;
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

    public function setIdTamano($value){
        if ($this->validateNaturalNumber($value)) {
            $this->id_tamano = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdEstado($value){
        if ($this->validateNaturalNumber($value)) {
            $this->id_estado = $value;
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

    public function getFecha()
    {
        return $this->fecha_pedidopersonal;
    }

    public function getDescripPedido()
    {
        return $this->descripcion_pedidopersonal;
    }

    public function getImagen()
    {
        return $this->imagenejemplo_pedidopersonal;
    }

    public function getDescripLugar()
    {
        return $this->descripcionlugar_entrega;
    }

    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    public function getIdTamano()
    {
        return $this->id_tamano;
    }

    public function getEstado()
    {
        return $this->id_estado;
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar pedido por x cliente
    public function buscarPedidoPerC($value)
    {
        $sql = 'SELECT pp.id_pedidos_personalizado,pp.fecha_pedidopersonal,pp.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,pp.fk_id_tamano,tmn.tamano,
                pp.fk_id_estado,std.estado
                FROM pedidos_personalizados as pp 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN tamano AS tmn ON vcl.fk_id_tamano = tmn.id_tamano
                INNER JOIN estado	AS std ON vcl.fk_id_estado = std.id_estados
                WHERE pp.fk_id_cliente = ?
                ORDER BY pp.id_pedidos_personalizado';
        $params = $value;
        return Database::getRows($sql, $params);
    }

    //Buscar pedido 
    public function buscarPedidoPer($value)
    {
        $sql = 'SELECT pp.id_pedidos_personalizado,pp.fecha_pedidopersonal,pp.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,pp.fk_id_tamano,tmn.tamano,
                pp.fk_id_estado,std.estado
                FROM pedidos_personalizados as pp 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN tamano AS tmn ON vcl.fk_id_tamano = tmn.id_tamano
                INNER JOIN estado	AS std ON vcl.fk_id_estado = std.id_estados
                WHERE pp.id_pedidos_personalizado = ?
                ORDER BY pp.id_pedidos_personalizado';
        $params = $value;
        return Database::getRows($sql, $params);
    }

    //Buscar pedido por x tamaño
    public function buscarPedidoPerT($value)
    {
        $sql = 'SELECT pp.id_pedidos_personalizado,pp.fecha_pedidopersonal,pp.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,pp.fk_id_tamano,tmn.tamano,
                pp.fk_id_estado,std.estado
                FROM pedidos_personalizados as pp 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN tamano AS tmn ON vcl.fk_id_tamano = tmn.id_tamano
                INNER JOIN estado	AS std ON vcl.fk_id_estado = std.id_estados
                WHERE pp.fk_id_tamano = ?
                ORDER BY pp.id_pedidos_personalizado';
        $params = $value;
        return Database::getRows($sql, $params);
    }

    //mostrar todas las columnas de pedidos

    public function obtenerPedidoP()
    {
        $sql = 'SELECT id_pedidos_personalizado, fecha_pedidopersonal, descripcion_pedidopersonal, imagenejemplo_pedidopersonal, descripcionlugar_entrega,
                fk_id_cliente, fk_id_tamano, fk_id_estado
                FROM pedidos_personalizados';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //obtener columna de tamaño por id

    public function obtenerPedidoP()
    {
        $sql = 'SELECT id_pedidos_personalizado, fecha_pedidopersonal, descripcion_pedidopersonal, imagenejemplo_pedidopersonal, descripcionlugar_entrega,
                fk_id_cliente, fk_id_tamano, fk_id_estado
                FROM pedidos_personalizados
                WHERE id_pedidos_personalizado = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    //Crear columna de pedido

    public function crearPedidoP()
    {
        $sql = 'INSERT INTO pedidos_personalizados(fecha_pedidopersonal, descripcion_pedidopersonal, imagenejemplo_pedidopersonal, descripcionlugar_entrega, fk_id_cliente, fk_id_tamano, fk_id_estado)
                VALUES(?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->fecha_pedidopersonal, $this->descripcion_pedidopersonal, $this->imagenejemplo_pedidopersonal, $this->descripcionlugar_entrega, 
        $this->id_cliente, $this->id_tamano, $this->id_estado);
        return Database::executeRow($sql, $params);
    }

    //Eliminar valoracion del pedido

    public eliminarPedidoP(){
        $sql = 'DELETE FROM pedidos_personalizados
                WHERE id_pedidos_personalizado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}