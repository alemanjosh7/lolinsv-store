<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/inventario.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $inventario = new Inventario;
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
                if ($result['dataset'] = $inventario->readProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Administrador inexistente';
                }
                break;
            case 'editProfile':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$inventario->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$inventario->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($inventario->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'changePassword':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->setId($_SESSION['id_admins'])) {
                    $result['exception'] = 'admins incorrecto';
                } elseif (!$inventario->checkPassword($_POST['actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                } elseif ($_POST['nueva'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$inventario->setClave($_POST['nueva'])) {
                    $result['exception'] = $inventario->getPasswordError();
                } elseif ($inventario->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $inventario->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = $inventario->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $inventario->buscarInv($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'readAllLimit':
                if ($result['dataset'] = $inventario->obtenerInventarioLt($_POST['limit'])){
                    $result['status'] = 1;
                    $result['message'] = 'Registros encontrados';
                } elseif (Database::getException()){
                    $result['exception'] = Database::getException();
                } else{
                    $result['exception'] = '¡Lo sentimos! No hay resgistros';
                }
                break;
            case 'create':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->setCantidada($_POST['cantidada'])) {
                    $result['exception'] = 'Cantidad actual incorrecta';
                } elseif (!$inventario->setCantidadn($_POST['cantidadn'])) {
                    $result['exception'] = 'Cantidad a registrar incorrecta';
                } elseif (!$inventario->setIdProd($_POST['codproducto'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($inventario->crearInventario()) {
                    $result['status'] = 1;
                    $result['message'] = 'Registro realizado y cantidad del producto actualizada';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$inventario->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif ($result['dataset'] = $inventario->obtenerCliente()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Cliente inexistente';
                }
                break;
            case 'update':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->setId($_POST['idinventario'])) {
                    $result['exception'] = 'Registro incorrecto';
                } elseif (!$inventario->buscarInventario()) {
                    $result['exception'] = 'Registro del inventario inexistente';
                } elseif (!$inventario->setCantidadn($_POST['cantidadn'])) {
                    $result['exception'] = 'Cantidad nueva a ingresar invalida';
                } elseif (!$inventario->setCantidada($_POST['cantidadact'])) {
                    $result['exception'] = 'Cantidad previo a ingreso invalida';
                } elseif (!$inventario->setIdProd($_POST['codproductoh'])) {
                    $result['exception'] = 'codigo del producto invalido';
                } elseif ($inventario->actualizarInv()) {
                    $result['status'] = 1;
                    if($inventario->actualizarCanPrd()){
                        $result['message'] = 'Registro del inventario modificado correctamente y cantidad del producto corregida';
                    }else{
                        $result['message'] = 'Registro del inventario modificado correctamente pero la cantidad del producto no se pudo modificar, modificarla desde productos';
                    }
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$inventario->setId($_POST['id'])) {
                    $result['exception'] = 'Registro invalido';
                } elseif (!$inventario->buscarInventario()) {
                    $result['exception'] = 'Registro inexistente';
                } elseif ($inventario->eliminarInventario()) {
                    $result['status'] = 1;
                    $result['message'] = 'Registro eliminado';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'actualizarContraCli':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente invalido';
                } elseif (!$inventario->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $inventario->getPasswordError();
                }elseif ($inventario->cambiarContrasenaCl()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña de cliente actualizada';
                } else {
                    $result['exception'] = 'La contraseña no se pudo actualizar';
                }
                break;
            case 'buscarImg':
                if($result['dataset'] = $inventario->imgProducto($_POST['id'])){
                    $result['status'] = 1;
                }elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo obtener la imagen del producto';
                }
                break;
            case 'nombreApellido':
                if ($result['dataset'] = $inventario->nombreApellidoAdminL()) {
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
                if ($inventario->obtenerAdmins()){
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un administrador registrado';
                } else {
                    $result['exception'] = 'No existen administrador registrados';
                }
                break;
            case 'register':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->setNombre_admin($_POST['nombre'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$inventario->setApellido_admin($_POST['apellido'])) {
                    $result['exception'] = 'Apellidos invalido';
                } elseif (!$inventario->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario invalido';
                } elseif (!$inventario->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $inventario->getPasswordError();
                } elseif ($inventario->crearAdmin()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'logIn':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->checkAdmin($_POST['usuario'])) {
                    $result['exception'] = 'Nombre de usuario incorrecto';
                } elseif ($inventario->checkContrasenaADM($_POST['contrasena'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_usuario'] = $inventario->getId_admin();
                    $_SESSION['usuario'] = $inventario->getUsuario();
                    $_SESSION['saludoI'] = false;
                    $inventario->nombreApellidoAdminL();
                }else {
                    $result['exception'] = 'Contraseña incorrecta';
                }
                break;
            case 'actualizarContraLog':
                $_POST = $inventario->validateForm($_POST);
                if (!$inventario->checkAdmin($_POST['usuario'])) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$inventario->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $inventario->getPasswordError();
                } elseif ($inventario->cambiarContrasenaCli()) {
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