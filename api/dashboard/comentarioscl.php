<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/valoracionesCliente.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $valoracionescl = new ValoracionesCliente;
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
                if ($result['dataset'] = $valoracionescl->readProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Administrador inexistente';
                }
                break;
            case 'editProfile':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$valoracionescl->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$valoracionescl->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($valoracionescl->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'changePassword':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->setId($_SESSION['id_admins'])) {
                    $result['exception'] = 'admins incorrecto';
                } elseif (!$valoracionescl->checkPassword($_POST['actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                } elseif ($_POST['nueva'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$valoracionescl->setClave($_POST['nueva'])) {
                    $result['exception'] = $valoracionescl->getPasswordError();
                } elseif ($valoracionescl->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $valoracionescl->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = $valoracionescl->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $valoracionescl->buscarValoracionG($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'readAllLimit':
                if ($result['dataset'] = $valoracionescl->obtenerValoracionesCL($_POST['limit'])){
                    $result['status'] = 1;
                    $result['message'] = 'Comentarios encontrados';
                } elseif (Database::getException()){
                    $result['exception'] = Database::getException();
                } else{
                    $result['exception'] = '¡Lo sentimos! No hay comentarios registrados';
                }
                break;
            case 'create':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$valoracionescl->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$valoracionescl->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$valoracionescl->setAlias($_POST['alias'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($_POST['clave'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$valoracionescl->setClave($_POST['clave'])) {
                    $result['exception'] = $valoracionescl->getPasswordError();
                } elseif ($valoracionescl->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$valoracionescl->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif ($result['dataset'] = $valoracionescl->obtenerCliente()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Cliente inexistente';
                }
                break;
            case 'update':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif (!$valoracionescl->obtenerCliente()) {
                    $result['exception'] = 'Cliente inexistente';
                } elseif (!$valoracionescl->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$valoracionescl->setApellido($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos invalido';
                } elseif (!$valoracionescl->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo invalido';
                } elseif (!$valoracionescl->setDUI($_POST['dui'])) {
                    $result['exception'] = 'DUI invalido';
                } elseif (!$valoracionescl->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono invalido';
                } elseif (!$valoracionescl->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario invalido';
                } elseif (!$valoracionescl->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion invalida';
                } elseif (!$valoracionescl->setEstado(isset($_POST['estado']) ? 8 : 9)) {
                    $result['exception'] = 'DUI invalido';
                } elseif ($valoracionescl->actualizarCliente()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$valoracionescl->setId($_POST['id'])) {
                    $result['exception'] = 'Comentario invalido';
                } elseif (!$valoracionescl->buscarValoracion($_POST['id'])) {
                    $result['exception'] = 'Comentario inexistente';
                } elseif ($valoracionescl->eliminarValoracionCli()) {
                    $result['status'] = 1;
                    $result['message'] = 'Comentario/Valoración eliminada';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'actualizarContraCli':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente invalido';
                } elseif (!$valoracionescl->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $valoracionescl->getPasswordError();
                }elseif ($valoracionescl->cambiarContrasenaCl()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña de cliente actualizada';
                } else {
                    $result['exception'] = 'La contraseña no se pudo actualizar';
                }
                break;
            case 'nombreApellido':
                if ($result['dataset'] = $valoracionescl->nombreApellidoAdminL()) {
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
                if ($valoracionescl->obtenerAdmins()){
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un administrador registrado';
                } else {
                    $result['exception'] = 'No existen administrador registrados';
                }
                break;
            case 'register':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->setNombre_admin($_POST['nombre'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$valoracionescl->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos invalido';
                } elseif (!$valoracionescl->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario invalido';
                } elseif (!$valoracionescl->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $valoracionescl->getPasswordError();
                } elseif ($valoracionescl->crearAdmin()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'logIn':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->checkAdmin($_POST['usuario'])) {
                    $result['exception'] = 'Nombre de usuario incorrecto';
                } elseif ($valoracionescl->checkContrasenaADM($_POST['contrasena'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_usuario'] = $valoracionescl->getId_admin();
                    $_SESSION['usuario'] = $valoracionescl->getUsuario();
                    $_SESSION['saludoI'] = false;
                    $valoracionescl->nombreApellidoAdminL();
                }else {
                    $result['exception'] = 'Contraseña incorrecta';
                }
                break;
            case 'actualizarContraLog':
                $_POST = $valoracionescl->validateForm($_POST);
                if (!$valoracionescl->checkAdmin($_POST['usuario'])) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$valoracionescl->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $valoracionescl->getPasswordError();
                } elseif ($valoracionescl->cambiarContrasenaCli()) {
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