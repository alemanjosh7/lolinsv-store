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
            case 'getUser':
                if (isset($_SESSION['alias_admins'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['alias_admins'];
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
                $_POST = $admins->validateForm($_POST);
                if (!$admins->setId_admin($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Admin incorrecto';
                } elseif (!$admins->checkContrasenaADM($_POST['contrasena_actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                    $result['message'] = $_POST['contrasena_actual'];
                } elseif ($_POST['contrasena_nueva'] != $_POST['contrasena_confirma']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$admins->setContrasena($_POST['contrasena_nueva'])) {
                    $result['exception'] = $admins->getPasswordError();
                } elseif ($admins->cambiarContrasenaADM()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $admins->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
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
                if (!$admins->setNombre_admin($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$admins->setApellido_admin($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$admins->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$admins->setAlias($_POST['alias'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($_POST['clave'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$admins->setClave($_POST['clave'])) {
                    $result['exception'] = $admins->getPasswordError();
                } elseif ($admins->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$admins->setId($_POST['id'])) {
                    $result['exception'] = 'Administrador incorrecto';
                } elseif ($result['dataset'] = $admins->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'admins inexistente';
                }
                break;
            case 'update':
                $_POST = $admins->validateForm($_POST);
                if (!$admins->setId($_POST['id'])) {
                    $result['exception'] = 'administrador incorrecto';
                } elseif (!$admins->readOne()) {
                    $result['exception'] = 'administrador inexistente';
                } elseif (!$admins->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$admins->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$admins->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($admins->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'administrador modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if ($_POST['id'] == $_SESSION['id_admins']) {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                } elseif (!$admins->setId($_POST['id'])) {
                    $result['exception'] = 'administrador incorrecto';
                } elseif (!$admins->readOne()) {
                    $result['exception'] = 'administrador inexistente';
                } elseif ($admins->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'administrador eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'nombreApellido':
                if ($result['dataset'] = $admins->nombreApellidoAdminL()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo obtener la información necesaria para el saludo';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $admins->getProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
                break;
            case 'editProfile':
                $_POST = $admins->validateForm($_POST);
                if (!$admins->setNombre_admin($_POST['nombre'])) {
                    $result['exception'] = 'Nombres incorrectos';
                    $result['message'] = $_POST['nombre'];
                } elseif (!$admins->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                }elseif (!$admins->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($admins->updateProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                    $admins->nombreApellidoAdminL();
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    }else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($admins->obtenerAdmins()){
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un administrador registrado';
                } else {
                    $result['exception'] = 'No existen administrador registrados';
                }
                break;
            case 'register':
                $_POST = $admins->validateForm($_POST);
                if (!$admins->setNombre_admin($_POST['nombre'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$admins->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos invalido';
                } elseif (!$admins->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario invalido';
                } elseif (!$admins->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $admins->getPasswordError();
                } elseif ($admins->crearAdmin()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'logIn':
                $_POST = $admins->validateForm($_POST);
                if (!$admins->checkAdmin($_POST['usuario'])) {
                    $result['exception'] = 'Nombre de usuario incorrecto';
                }if (!$admins->checkAdminLog()) {
                    $result['exception'] = 'Nombre de usuario eliminado';
                } elseif ($admins->checkContrasenaADM($_POST['contrasena'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_usuario'] = $admins->getId_admin();
                    $_SESSION['usuario'] = $admins->getUsuario();
                    $_SESSION['saludoI'] = false;
                    $admins->nombreApellidoAdminL();
                }else {
                    $result['exception'] = 'Contraseña incorrecta';
                }
                break;
            case 'actualizarContraLog':
                $_POST = $admins->validateForm($_POST);
                if (!$admins->checkAdmin($_POST['usuario'])) {
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
