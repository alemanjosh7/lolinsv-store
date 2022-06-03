<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/pedidosPersonalizados.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedidosami = new Pedidos_personalizados;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_cliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'create':
                $_POST = $pedidosami->validateForm($_POST);
                if (!$pedidosami->setDescripPedido($_POST['desc_pedido'])) {
                    $result['exception'] = 'Descripción del pedido invalida';
                } elseif (!$pedidosami->setDescripLugar($_POST['desc_lugar'])) {
                    $result['exception'] = 'Descripción del lugar de entrega invalida';
                } elseif (!isset($_POST['tamano'])) {
                    $result['exception'] = 'Seleccione un tamaño';
                } elseif (!$pedidosami->setIdTamano($_POST['tamano'])) {
                    $result['exception'] = 'Tamaño incorrecto';
                } elseif (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                    $result['exception'] = 'Seleccione una imagen';
                } elseif (!$pedidosami->setImagen($_FILES['archivo'])) {
                    $result['exception'] = $pedidosami->getFileError();
                } elseif ($pedidosami->crearPedidoPer()) {
                    $result['status'] = 1;
                    if ($pedidosami->saveFile($_FILES['archivo'], $pedidosami->getRuta(), $pedidosami->getImagen())) {
                        $result['message'] = 'Pedido creado correctamente';
                    } else {
                        $result['message'] = 'Pedido creado pero no se guardó la imagen';
                    }
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando un cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'create':
                $result['exception'] = 'Debe iniciar sesión para realizar un pedido personalizado';
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