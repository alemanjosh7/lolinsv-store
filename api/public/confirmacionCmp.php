<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/pedidosEstablecidos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $confirmacion_cmp = new Pedidos_establecidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_cliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'obtenerPedido':
                if (isset($_SESSION['id_pedidoEsta'])) {
                    if (!$confirmacion_cmp->setId($_SESSION['id_pedidoEsta'])) {
                        $result['exception'] = 'Id del pedido incorrecto';
                    } elseif ($result['dataset'] = $confirmacion_cmp->readPedido()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Pedido inexistente';
                    }
                } else {
                    $result['exception'] = 'No hay un pedido que mostrar';
                }
                break;
            case 'obtenerPedidoVendido':
                if (!$confirmacion_cmp->setId($_POST['id'])) {
                    $result['exception'] = 'Id del pedido incorrecto';
                } elseif ($result['dataset'] = $confirmacion_cmp->readPedido()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Pedido inexistente';
                }
                break;
            case 'obtenerOrden':
                if (isset($_SESSION['id_pedidoEsta'])) {
                    if (!$confirmacion_cmp->setId($_SESSION['id_pedidoEsta'])) {
                        $result['exception'] = 'Id del pedido incorrecto';
                    } elseif ($result['dataset'] = $confirmacion_cmp->ObtenerDetalle()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Pedido inexistente';
                    }
                } else {
                    $result['exception'] = 'No hay un pedido que mostrar';
                }
                break;
            case 'update':
                $_POST = $confirmacion_cmp->validateForm($_POST);
                if (!$confirmacion_cmp->setId($_SESSION['id_pedidoEsta'])) {
                    $result['exception'] = 'Pedido establecidos incorrecto';
                } elseif (!$confirmacion_cmp->readPedido()) {
                    $result['exception'] = 'Pedido inexistente';
                } elseif (!$confirmacion_cmp->setDescripLugar($_POST['descl'])) {
                    $result['exception'] = 'Descripción de lugar inaceptable';
                } elseif (!$confirmacion_cmp->setMonto($_POST['total'])) {
                    $result['exception'] = 'Monto Total incorrecto';
                } elseif ($confirmacion_cmp->confirmarVenta()) {
                    $result['status'] = 1;
                    $result['message'] = 'Compra realizada correctamente ¡Gracias por su compra!';
                    unset($_SESSION['id_pedidoEsta']);
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$confirmacion_cmp->setId($_SESSION['id_pedidoEsta'])) {
                    $result['exception'] = 'Pedido incorrecto';
                } elseif (!$confirmacion_cmp->readPedido()) {
                    $result['exception'] = 'Pedido inexistente';
                } elseif ($confirmacion_cmp->deletePedido()) {
                    $result['status'] = 1;
                    $result['message'] = 'Carrito y pedido limpiado';
                    unset($_SESSION['id_pedidoEsta']);
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        $result['exception'] = 'Necesita Loguearse para poder visualizar el carrito';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
