<?php
/*
*	Clase para manejar la tabla Valoraciones de la base de datos.
*   Es clase hija de Validator.
*/
class Pedidos_establecidos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;//Id del tamaño
    private $fecha_pedidoesta = null;//fecha 
    private $descripcionlugar_entrega = null;//descripcion del lugar
    private $montototal_pedidoesta = null;//monto
    private $id_cliente = null;//id del cliente llave foranea
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
        if ($this->Validatedate($value)) {
            $this->fecha_pedidopersonal = $value;
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
    
    public function setMonto($value){
        if ($this->validateMoney($value)) {
            $this->montototal_pedidoesta = $value;
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

    public function getDescripLugar()
    {
        return $this->descripcionlugar_entrega;
    }
    
    public function getMonto()
    {
        return $this->montototal_pedidoesta;
    }

    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    public function getEstado()
    {
        return $this->id_estado;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar Pedido
    public function searchPedido($value)
    {
        $sql = 'SELECT pes.id_pedidos_establecidos, pes.fecha_pedidoesta, pes.descripcionlugar_entrega, pes.montototal_pedidoesta, clt.nombre_cliente, clt.apellido_cliente
        FROM pedidos_establecidos as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        WHERE pes.fk_id_estado=1 and clt.nombre_cliente ILIKE ? OR clt.apellido_cliente ILIKE ? OR cast(pes.id_pedidos_establecidos as varchar)ILIKE ? OR cast(pes.montototal_pedidoesta as varchar) ILIKE ? OR cast(pes.fecha_pedidoesta as varchar) ILIKE ?
        ORDER BY pes.id_pedidos_establecidos DESC';
        $params = array("%$value%", "%$value%", "%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function searchPedidoEnt($value)
    {
        $sql = 'SELECT pes.id_pedidos_establecidos, pes.fecha_pedidoesta, pes.descripcionlugar_entrega, pes.montototal_pedidoesta, clt.nombre_cliente, clt.apellido_cliente
        FROM pedidos_establecidos as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        WHERE and pes.fk_id_estado=2 and clt.nombre_cliente ILIKE ? OR clt.apellido_cliente ILIKE ? OR cast(pes.id_pedidos_establecidos as varchar)ILIKE ? OR cast(pes.montototal_pedidoesta as varchar) ILIKE ? OR cast(pes.fecha_pedidoesta as varchar) ILIKE ?
        ORDER BY pes.id_pedidos_establecidos DESC';
        $params = array("%$value%", "%$value%", "%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    //Mostrar los pedidos
    public function readPedido()
    {
        $sql = 'SELECT pes.id_pedidos_establecidos, pes.fecha_pedidoesta, pes.descripcionlugar_entrega, pes.montototal_pedidoesta, clt.nombre_cliente, clt.apellido_cliente, clt.direccion_cliente, clt.correo_cliente
        FROM pedidos_establecidos as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        WHERE pes.id_pedidos_establecidos = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //Cambiar estado a enviado
    public function cambiarEstado()
    {
        $sql='update pedidos_establecidos set fk_id_estado=2 where id_pedidos_establecidos=?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Obteniendo detalle del pedido
    public function ObtenerDetalle()
    {
        $sql ='SELECT det.id_detalle_pedidos,det.cantidad_detallep, det.subtotal_detallep, prd.nombre_producto, prd.precio_producto, prd.imagen_producto, pes.montototal_pedidoesta, pes.id_pedidos_establecidos
               FROM detallepedidos_establecidos AS det
               INNER JOIN productos AS prd ON det.fk_id_producto = prd.id_producto
		       INNER JOIN pedidos_establecidos AS pes ON det.fk_id_pedidos_establecidos = pes.id_pedidos_establecidos
               WHERE det.fk_id_pedidos_establecidos = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    //Eliminar Pedido
    public function deletePedido(){
        $sql = 'UPDATE pedidos_establecidos SET fk_id_estado = 10
                WHERE id_pedidos_establecidos = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function limitPendiente($limit)
    {
        $sql = 'SELECT pes.id_pedidos_establecidos, pes.fecha_pedidoesta, pes.descripcionlugar_entrega, pes.montototal_pedidoesta, clt.nombre_cliente, clt.apellido_cliente
        FROM pedidos_establecidos as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        WHERE pes.id_pedidos_establecidos NOT IN(SELECT id_pedidos_establecidos FROM pedidos_establecidos LIMIT ?) AND pes.fk_id_estado = 1
        ORDER BY pes.id_pedidos_establecidos DESC LIMIT 8;';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }

    public function limitEntregado($limit)
    {
        $sql = 'SELECT pes.id_pedidos_establecidos, pes.fecha_pedidoesta, pes.descripcionlugar_entrega, pes.montototal_pedidoesta, clt.nombre_cliente, clt.apellido_cliente
        FROM pedidos_establecidos as pes
        INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
        WHERE pes.id_pedidos_establecidos NOT IN(SELECT id_pedidos_establecidos FROM pedidos_establecidos LIMIT ?) AND pes.fk_id_estado = 2
        ORDER BY pes.id_pedidos_establecidos DESC LIMIT 8;';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }
    //Obtener el detalle del carrito
    public function ObtenerDetalleC(){
        $sql ='SELECT det.id_detalle_pedidos,det.cantidad_detallep, det.subtotal_detallep, prd.nombre_producto, prd.precio_producto, prd.imagen_producto, pes.montototal_pedidoesta, pes.id_pedidos_establecidos
               FROM detallepedidos_establecidos AS det
               INNER JOIN productos AS prd ON det.fk_id_producto = prd.id_producto
		       INNER JOIN pedidos_establecidos AS pes ON det.fk_id_pedidos_establecidos = pes.id_pedidos_establecidos
               WHERE det.id_detalle_pedidos = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
    //Eliminar el detalle del carrito
    public function eliminarDetalle(){
        $sql = 'DELETE FROM detallepedidos_establecidos
                WHERE id_detalle_pedidos = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
    //Actualizar el monto total de pedidos establecidos
    public function actualizarMontoT($idp){
        $sql = 'UPDATE pedidos_establecidos 
                SET montototal_pedidoesta = (SELECT SUM(subtotal_detallep) FROM detallepedidos_establecidos WHERE fk_id_pedidos_establecidos = ?) 
                WHERE id_pedidos_establecidos=?';
        $params = array($idp,$idp);
        return Database::executeRow($sql, $params);
    }
    //Confirmar la venta del pedido
    /*Se cambiara su estado a pendiente, se añadira la descripción de la entrega y se actualizara el montotal sumando los $5 dolares*/
    public function confirmarVenta(){
        $sql = 'UPDATE pedidos_establecidos 
                SET montototal_pedidoesta = ?, fk_id_estado = 1, descripcionlugar_entrega = ? 
                WHERE id_pedidos_establecidos = ?';
        $params = array($this->montototal_pedidoesta,$this->descripcionlugar_entrega, $_SESSION['id_pedidoEsta']);
        return Database::executeRow($sql, $params);
    }
}
?>
