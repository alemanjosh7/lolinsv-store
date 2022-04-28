<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/clientes.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $clientes = new Clientes;
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
            case 'readProfile':
                if ($result['dataset'] = $clientes->readProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Administrador inexistente';
                }
                break;
            case 'editProfile':
                $_POST = $clientes->validateForm($_POST);
                if (!$clientes->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$clientes->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$clientes->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($clientes->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'changePassword':
                $_POST = $clientes->validateForm($_POST);
                if (!$clientes->setId($_SESSION['id_admins'])) {
                    $result['exception'] = 'admins incorrecto';
                } elseif (!$clientes->checkPassword($_POST['actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                } elseif ($_POST['nueva'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$clientes->setClave($_POST['nueva'])) {
                    $result['exception'] = $clientes->getPasswordError();
                } elseif ($clientes->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $clientes->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = $clientes->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $clientes->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'readAllLimit':
                if ($result['dataset'] = $clientes->obtenerClientesLimit($_POST['limit'])){
                    $result['status'] = 1;
                    $result['message'] = 'Clientes encontrados';
                } elseif (Database::getException()){
                    $result['exception'] = Database::getException();
                } else{
                    $result['exception'] = '¡Lo sentimos! No hay clientes registrados';
                }
                break;
            case 'create':
                $_POST = $clientes->validateForm($_POST);
                if (!$clientes->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$clientes->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$clientes->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$clientes->setAlias($_POST['alias'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($_POST['clave'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$clientes->setClave($_POST['clave'])) {
                    $result['exception'] = $clientes->getPasswordError();
                } elseif ($clientes->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$clientes->setId($_POST['id'])) {
                    $result['exception'] = 'Administrador incorrecto';
                } elseif ($result['dataset'] = $clientes->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'admins inexistente';
                }
                break;
            case 'update':
                $_POST = $clientes->validateForm($_POST);
                if (!$clientes->setId($_POST['id'])) {
                    $result['exception'] = 'administrador incorrecto';
                } elseif (!$clientes->readOne()) {
                    $result['exception'] = 'administrador inexistente';
                } elseif (!$clientes->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$clientes->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$clientes->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($clientes->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'administrador modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if ($_POST['id'] == $_SESSION['id_admins']) {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                } elseif (!$clientes->setId($_POST['id'])) {
                    $result['exception'] = 'administrador incorrecto';
                } elseif (!$clientes->readOne()) {
                    $result['exception'] = 'administrador inexistente';
                } elseif ($clientes->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'administrador eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'nombreApellido':
                if ($result['dataset'] = $clientes->nombreApellidoAdminL()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo obtener la información necesaria para el saludo';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($clientes->obtenerAdmins()){
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un administrador registrado';
                } else {
                    $result['exception'] = 'No existen administrador registrados';
                }
                break;
            case 'register':
                $_POST = $clientes->validateForm($_POST);
                if (!$clientes->setNombre_admin($_POST['nombre'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$clientes->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos invalido';
                } elseif (!$clientes->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario invalido';
                } elseif (!$clientes->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $clientes->getPasswordError();
                } elseif ($clientes->crearAdmin()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'logIn':
                $_POST = $clientes->validateForm($_POST);
                if (!$clientes->checkAdmin($_POST['usuario'])) {
                    $result['exception'] = 'Nombre de usuario incorrecto';
                } elseif ($clientes->checkContrasenaADM($_POST['contrasena'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_usuario'] = $clientes->getId_admin();
                    $_SESSION['usuario'] = $clientes->getUsuario();
                    $_SESSION['saludoI'] = false;
                    $clientes->nombreApellidoAdminL();
                }else {
                    $result['exception'] = 'Contraseña incorrecta';
                }
                break;
            case 'actualizarContraLog':
                $_POST = $clientes->validateForm($_POST);
                if (!$clientes->checkAdmin($_POST['usuario'])) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$clientes->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $clientes->getPasswordError();
                } elseif ($clientes->cambiarContrasenaADM()) {
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
?>