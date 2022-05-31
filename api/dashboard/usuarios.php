<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/admins.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $admins = new Admins;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'actContraUsuario':
                $_POST = $admins->validateForm($_POST);
                if (!$admins->setId_admin($_POST['id'])) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$admins->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $admins->getPasswordError();
                } elseif ($admins->cambiarContrasenaADM()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = 'La contraseña no se pudo actualizar';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $admins->obtenerAdmins()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readAllLimit':
                if ($result['dataset'] = $admins->obtenerAdminsLimit($_POST['limit'])) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay usuarios registradas';
                }
                break;
            case 'search':
                $_POST = $admins->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $admins->buscarAdmins($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'create':
                $_POST = $admins->validateForm($_POST);
                if (!$admins->setNombre_admin($_POST['nombre_admin'])) {
                    $admins['exception'] = 'Nombres incorrectos';
                } elseif (!$admins->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$admins->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($_POST['contrasena'] != $_POST['coContrasena']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$admins->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $admins->getPasswordError();
                } elseif ($admins->crearAdmin()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'update':
                if (!$admins->setId_admin($_POST['id_admin'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif (!$admins->obtenerAdmin()) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$admins->setNombre_admin($_POST['nombre_admin'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$admins->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$admins->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif ($admins->actualizarAdmin()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if ($_POST['id_admin'] == $_SESSION['id_usuario']) {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                } elseif (!$admins->setId_admin($_POST['id_admin'])) {
                    $result['exception'] = 'Usuario incorrecta';
                } elseif (!$data = $admins->obtenerAdmin()) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif ($admins->cambiarEstadoAdm()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$admins->setId_admin($_POST['id_admin'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($result['dataset'] = $admins->obtenerAdmin()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Cliente inexistente';
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