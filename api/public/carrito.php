<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/pedidosEstablecidos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $carrito = new Pedidos_establecidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_cliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'obtenerCarrito':
                if (!$carrito->setId($_POST['id'])) {
                    $result['exception'] = 'Pedido establecido incorrecto';
                } elseif ($result['dataset'] = $carrito->ObtenerDetalle()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Pedido inexistente inexistente';
                }
                break;
            case 'delete':
                if (!$carrito->setId($_POST['id'])) {
                    $result['exception'] = 'Detalle incorrecto';
                } elseif (!$carrito->ObtenerDetalleC()) {
                    $result['exception'] = 'Detalle inexistente';
                } elseif ($carrito->eliminarDetalle()) {
                    $result['status'] = 1;
                    if ($carrito->actualizarCantPrdDtl($_POST['cantidad'], $_POST['idprd'])) {
                        if ($carrito->actualizarMontoT($_POST['idp'])) {
                            $result['message'] = 'Producto eliminado correctamente del carrito, aun puedes volverlo a añadir';
                        } else {
                            $result['message'] = 'Producto eliminado del carrito pero no se pudo actualizar el monto total del pedido';
                        }
                    } else {
                        //$result['exception'] = 'Producto eliminado del carrito pero no se pudo actualizar su monto total';
                        $result['exception'] = Database::getException();
                    }
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
