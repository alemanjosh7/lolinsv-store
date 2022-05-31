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
    private $ruta = '../images/pedidosper/';
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

    public function getRuta()
    {
        return $this->ruta;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar pedido
    public function searchPedido($value)
    {
        $sql = 'SELECT pes.id_pedidos_personalizado, pes.fecha_pedidopersonal, pes.descripcionlugar_entrega, clt.nombre_cliente, clt.apellido_cliente
        FROM pedidos_personalizados as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        WHERE pes.fk_id_estado=1 and clt.nombre_cliente ILIKE ? OR clt.apellido_cliente ILIKE ? OR cast(pes.id_pedidos_personalizado as varchar)ILIKE ? OR cast(pes.fecha_pedidopersonal as varchar) ILIKE ?
        ORDER BY pes.id_pedidos_personalizado DESC';
        $params = array("%$value%", "%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function searchPedidoEnt($value)
    {
        $sql = 'SELECT pes.id_pedidos_personalizado, pes.fecha_pedidopersonal, pes.descripcionlugar_entrega, clt.nombre_cliente, clt.apellido_cliente
        FROM pedidos_personalizados as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        WHERE pes.fk_id_estado=2 and clt.nombre_cliente ILIKE ? OR clt.apellido_cliente ILIKE ? OR cast(pes.id_pedidos_personalizado as varchar)ILIKE ? OR cast(pes.fecha_pedidopersonal as varchar) ILIKE ? 
        ORDER BY pes.id_pedidos_personalizado DESC';
        $params = array("%$value%", "%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    //Mostrar pedido
    public function readPedido()
    {
        $sql = 'SELECT pes.id_pedidos_personalizado, pes.fecha_pedidopersonal, pes.descripcion_pedidopersonal, pes.imagenejemplo_pedidopersonal, pes.descripcionlugar_entrega,
        clt.nombre_cliente, clt.apellido_cliente, clt.direccion_cliente, tam.tamano
        FROM pedidos_personalizados as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
		INNER JOIN tamanos AS tam ON pes.fk_id_tamano = tam.id_tamanos
		WHERE pes.id_pedidos_personalizado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function deletePedido(){
        $sql = 'UPDATE pedidos_personalizados SET fk_id_estado = 10
                WHERE id_pedidos_personalizado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function limitPendiente($limit)
    {
        $sql = 'SELECT pes.id_pedidos_personalizado, pes.fecha_pedidopersonal, pes.descripcion_pedidopersonal, pes.imagenejemplo_pedidopersonal, 
        clt.nombre_cliente, clt.apellido_cliente, tm.tamano
        FROM pedidos_personalizados as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        INNER JOIN tamanos AS tm ON pes.fk_id_tamano = tm.id_tamanos
        WHERE pes.id_pedidos_personalizado NOT IN(SELECT id_pedidos_personalizado FROM pedidos_personalizados LIMIT ?) AND pes.fk_id_estado = 1
        ORDER BY pes.id_pedidos_personalizado DESC LIMIT 8;';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }

    public function limitEntregado($limit)
    {
        $sql = 'SELECT pes.id_pedidos_personalizado, pes.fecha_pedidopersonal, pes.descripcion_pedidopersonal, pes.imagenejemplo_pedidopersonal, 
        clt.nombre_cliente, clt.apellido_cliente, tm.tamano
        FROM pedidos_personalizados as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        INNER JOIN tamanos AS tm ON pes.fk_id_tamano = tm.id_tamanos
        WHERE pes.id_pedidos_personalizado NOT IN(SELECT id_pedidos_personalizado FROM pedidos_personalizados LIMIT ?) AND pes.fk_id_estado = 2
        ORDER BY pes.id_pedidos_personalizado DESC LIMIT 8;';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }

    public function cambiarEstadoAcep()
    {
        $sql='update pedidos_personalizados set fk_id_estado=6 where id_pedidos_personalizado=?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function cambiarEstadoNegar()
    {
        $sql='update pedidos_personalizados set fk_id_estado=5 where id_pedidos_personalizado=?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Función para crear un pedido
    public function crearPedidoPer()
    {
        $sql='INSERT INTO pedidos_personalizados(descripcion_pedidopersonal,imagenejemplo_pedidopersonal,descripcionlugar_entrega,fk_id_cliente,fk_id_tamano,fk_id_estado)
              VALUES(?,?,?,?,?,1)';
        $params = array($this->descripcion_pedidopersonal,$this->imagenejemplo_pedidopersonal,$this->descripcionlugar_entrega,$_SESSION['id_cliente'],$this->id_tamano);
        return Database::executeRow($sql, $params);
    }
}
