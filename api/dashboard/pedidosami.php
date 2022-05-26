<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/pedidos_Establecidos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedidosami = new Pedidos_establecidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) { 
                case 'readAllLimit':
                    if ($result['dataset'] = $pedidosami->limitEntregado($_POST['limit'])){
                        $result['status'] = 1;
                    } elseif (Database::getException()){
                        $result['exception'] = Database::getException();
                    } else{
                        $result['exception'] = 'No hay pedidos registradas';
                    }
                    break;
                    case 'search': 
                    $_POST = $pedidosami->validateForm($_POST);
                    if ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar'; 
                    } elseif ($result['dataset'] = $pedidosami->searchPedidoEnt($_POST['search'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Prueba encontrado'; 
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                break;
                        case 'readPedido':
                            if (!$pedidosami->setId($_POST['id_pedidos_establecidos'])) {
                                $result['exception'] = 'Pedido incorrecta';
                            } elseif ($result['dataset'] = $pedidosami->readPedido()) {
                                $result['status'] = 1;
                            } elseif (Database::getException()) {
                                $result['exception'] = Database::getException();
                            } else {
                                $result['exception'] = 'Pedido inexistente';
                            }
                        break;
                        case 'delete':
                            if (!$pedidosami->setId($_POST['id_pedidos_establecidos'])) {
                                $result['exception'] = 'Pedido incorrecto';
                            } elseif (!$data = $pedidosami->readPedido()) {
                                $result['exception'] = 'Pedido inexistente';
                            } elseif ($pedidosami->deletePedido()) {
                                $result['status'] = 1;
                            } else {
                                $result['exception'] = Database::getException();
                            }
                            break;
                        case'obtenerDetalle':
                            if (!$pedidosami->setId($_POST['id_pedidos_establecidos'])) {
                                $result['exception'] = 'Pedido incorrecta';
                            } elseif ($result['dataset'] = $pedidosami->ObtenerDetalle()) { 
                                $result['status'] = 1;
                            } elseif (Database::getException()) {
                                $result['exception'] = Database::getException();
                            } else {
                                $result['exception'] = 'No se pudo obtener el detalle';
                             }
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

//($_POST['limit']))