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
                if (isset($_SESSION['alias_carrito'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['alias_carrito'];
                } else {
                    $result['exception'] = 'Alias de administrador indefinido';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'changePassword':
                $_POST = $carrito->validateForm($_POST);
                if (!$carrito->setId_admin($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Admin incorrecto';
                } elseif (!$carrito->checkContrasenaADM($_POST['contrasena_actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                    $result['message'] = $_POST['contrasena_actual'];
                } elseif ($_POST['contrasena_nueva'] != $_POST['contrasena_confirma']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$carrito->setContrasena($_POST['contrasena_nueva'])) {
                    $result['exception'] = $carrito->getPasswordError();
                } elseif ($carrito->cambiarContrasenaADM()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $carrito->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = $carrito->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $carrito->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'create':
                $_POST = $carrito->validateForm($_POST);
                if (!$carrito->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$carrito->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$carrito->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$carrito->setAlias($_POST['alias'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($_POST['clave'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$carrito->setClave($_POST['clave'])) {
                    $result['exception'] = $carrito->getPasswordError();
                } elseif ($carrito->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$carrito->setId($_POST['id'])) {
                    $result['exception'] = 'Administrador incorrecto';
                } elseif ($result['dataset'] = $carrito->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'carrito inexistente';
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
                if ($_POST['id'] == $_SESSION['id_carrito']) {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                } elseif (!$carrito->setId($_POST['id'])) {
                    $result['exception'] = 'administrador incorrecto';
                } elseif (!$carrito->readOne()) {
                    $result['exception'] = 'administrador inexistente';
                } elseif ($carrito->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'administrador eliminado correctamente';
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
