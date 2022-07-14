<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/pedidosEstablecidos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedido = new PedidosEstablecidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['id_cliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            case 'createDetail':
                $_POST = $pedido->validateForm($_POST);
                if (!$pedido->validarCantidad($_POST['cantidad'])) {
                    $result['exception'] = 'La cantidad que esta ingresando es superior a la que se encuentra en existencias. Por favor verifique que sea menor o igual';
                } elseif (!$pedido->startOrder()) {
                    $result['exception'] = 'Ocurrió un problema al obtener el pedido';
                    $result['message'] = $pedido->getId_pedidos_establecidos();
                } elseif (!$pedido->setId_producto($_SESSION['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                    $result['message'] = $_SESSION['id_producto'];
                } elseif (!$pedido->setCantidad_detallep($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                    $result['message'] = $_SESSION['id_producto'];
                } elseif ($pedido->createDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto agregado correctamente';
                    if(!$pedido->actualizarSubT()){
                        $result['message'] = 'Se agrego al carrito, pero no se pudo actualizar su subtotal al detalle del pedido';
                    }elseif (!$pedido->actualizarMontoT($_SESSION['id_pedidoEsta'])) {
                        $result['message'] = 'Producto añadido, pero no se pudo actualizar el monto total del pedido';
                    }else{
                        $result['message'] = 'Producto agregado correctamente';
                    }
                } else {
                    $result['exception'] = 'Ocurrió un problema al agregar el producto';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando un cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'createDetail':
                $result['exception'] = 'Debe iniciar sesión para agregar el producto al carrito';
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
