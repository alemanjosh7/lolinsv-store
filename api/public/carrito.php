<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/Pedidos_establecidos.php');

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
            case 'update':
                $_POST = $carrito->validateForm($_POST);
                if (!$carrito->setId($_POST['id'])) {
                    $result['exception'] = 'administrador incorrecto';
                } elseif (!$carrito->readOne()) {
                    $result['exception'] = 'administrador inexistente';
                } elseif (!$carrito->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$carrito->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$carrito->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($carrito->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'administrador modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$carrito->setId($_POST['id'])) {
                    $result['exception'] = 'Detalle incorrecto';
                } elseif (!$carrito->ObtenerDetalleC()) {
                    $result['exception'] = 'Detalle inexistente';
                } elseif ($carrito->eliminarDetalle()) {
                    $result['status'] = 1;
                    if($carrito->actualizarMontoT($_POST['idp'])){
                        $result['message'] = 'Producto eliminado correctamente del carrito';
                    }else{
                        $result['message'] = 'Producto eliminado correctamente del carrito pero no se pudo actualizar su monto total';
                    }
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'nombreApellido':
                if ($result['dataset'] = $carrito->nombreApellidoAdminL()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo obtener la información necesaria para el saludo';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $carrito->getProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
                break;
            case 'editProfile':
                $_POST = $carrito->validateForm($_POST);
                if (!$carrito->setNombre_admin($_POST['nombre'])) {
                    $result['exception'] = 'Nombres incorrectos';
                    $result['message'] = $_POST['nombre'];
                } elseif (!$carrito->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                }elseif (!$carrito->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($carrito->updateProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                    $carrito->nombreApellidoAdminL();
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    }else {
        $result['exception'] = 'Necesita Loguearse para poder visualizar el carrito';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
?>