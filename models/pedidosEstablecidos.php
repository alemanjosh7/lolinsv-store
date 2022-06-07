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
    private $id_detalle_pedidos = null; //Id de detalle de pedidos
    private $cantidad_detallep = null; //cantidad
    private $subtotal_detallep = null; //subtotal
    private $id_producto = null; //id del producto llave foranea
    private $id_pedidos_establecidos = null; //id de pedidos establecidos llave foranea
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

    public function setSubtotal_detallep($value)
    {
        if ($this->validateMoney($value)) {
            $this->subtotal_detallep = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_producto($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_pedidos_establecidos($value)
    {
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
    //Crear pedido
    public function startOrder()
    {
        $this->id_estado = 7;
        $this->descripcionlugar_entrega = "";
        $this->montototal_pedidoesta = 0;
        $sql = 'SELECT id_pedidos_establecidos
                FROM pedidos_establecidos
                WHERE fk_id_estado = ? AND fk_id_cliente = ?';
        $params = array($this->id_estado, $_SESSION['id_cliente']);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_pedidos_establecidos = $data['id_pedidos_establecidos'];
            $_SESSION['id_pedidoEsta'] = $this->id_pedidos_establecidos;
            return true;
        } else {
            $sql = 'INSERT INTO pedidos_establecidos(descripcionlugar_entrega, montototal_pedidoesta, fk_id_cliente, fk_id_estado)
                    VALUES(?, ?, ?, ?)';
            $params = array($this->descripcionlugar_entrega, $this->montototal_pedidoesta, $_SESSION['id_cliente'], $this->id_estado);
            // Se obtiene el ultimo valor insertado en la llave primaria de la tabla pedidos.
            if ($this->id_pedidos_establecidos = Database::getLastRow($sql, $params)) {
                //Seteamos la variable de session
                $_SESSION['id_pedidoEsta'] = $this->id_pedidos_establecidos;
                return true;
            }
            return false;
        }
    }
    
    //Actualizar el subtotal del detalle del pedido al ingresar 
    public function actualizarSubT(){
        $sql = 'UPDATE detallepedidos_establecidos 
        SET subtotal_detallep = (SELECT dte.cantidad_detallep*prd.precio_producto FROM detallepedidos_establecidos AS dte, productos AS prd 
        WHERE dte.fk_id_producto = prd.id_producto AND id_detalle_pedidos = ?)
        WHERE id_detalle_pedidos = ?';
        $params = array($this->id_detalle_pedidos,$this->id_detalle_pedidos);
        return Database::executeRow($sql, $params);
    }

    //Crear el detalle
    public function createDetail()
    {
        $sql = 'SELECT id_detalle_pedidos FROM detallepedidos_establecidos WHERE fk_id_producto = ? AND fk_id_pedidos_establecidos = ?';
        $params = array($_SESSION['id_producto'], $this->id_pedidos_establecidos);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_detalle_pedidos = $data['id_detalle_pedidos'];
            
            $sql = 'UPDATE detallepedidos_establecidos SET cantidad_detallep = 
                    (SELECT cantidad_detallep FROM detallepedidos_establecidos WHERE id_detalle_pedidos = ?) + ? 
                    WHERE id_detalle_pedidos = ?';
            $params = array($this->id_detalle_pedidos, $this->cantidad_detallep, $this->id_detalle_pedidos);           
            return Database::executeRow($sql, $params);
        } else {
            $sql = 'INSERT INTO detallepedidos_establecidos(cantidad_detallep, subtotal_detallep, fk_id_producto, fk_id_pedidos_establecidos)
                VALUES(?, ?, ?, ?)';
            $params = array($this->cantidad_detallep, 0, $_SESSION['id_producto'], $this->id_pedidos_establecidos);
            return Database::executeRow($sql, $params);
        }
        // Se realiza una subconsulta para obtener el precio del producto.
    }

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
        $sql ='SELECT det.id_detalle_pedidos,det.cantidad_detallep, det.subtotal_detallep, det.fk_id_producto, prd.nombre_producto, prd.precio_producto, prd.imagen_producto, pes.montototal_pedidoesta, pes.id_pedidos_establecidos
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
        $sql ='SELECT det.id_detalle_pedidos,det.cantidad_detallep, det.fk_id_producto,det.subtotal_detallep, prd.nombre_producto, prd.precio_producto, prd.imagen_producto, pes.montototal_pedidoesta, pes.id_pedidos_establecidos
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
    //Actualizar la cantidad del producto tras eliminar el detalle del pedido para restablecer la cantidad original
    public function actualizarCantPrdDtl($cant,$idp){
        $sql = 'UPDATE productos SET cantidad = cantidad+? WHERE id_producto=?';
        $params = array(intval($cant),$idp);
        return Database::executeRow($sql, $params);
    }
    //Comprobar que la cantidad a añadir al pedido no sea mayor a la cantidad de existencias del producto
    public function validarCantidad($cantidad)
    {
        $sql = 'SELECT id_producto FROM productos WHERE cantidad>= ? AND id_producto = ?;';
        $params = array($cantidad, $_SESSION['id_producto']);
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
