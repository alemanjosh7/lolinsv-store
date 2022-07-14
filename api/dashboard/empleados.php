<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/empleados.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $empleados = new Empleados;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getUser':
                if (isset($_SESSION['alias_empleado'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['alias_empleado'];
                } else {
                    $result['exception'] = 'Alias de administrador indefinido';
                }
                break;
                //Log Out
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
                //Nombre apellido del empleado
            case 'nombreApellido':
                if ($result['dataset'] = $admins->nombreApellidoEmpleado()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo obtener la información necesaria para el saludo';
                }
                break;
                //Obtener el tipo de empleado
            case 'obtenerTipoEmpleado':
                if ($result['dataset'] = $empleados->obtenerTipoEmpleado()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Obtener todos los empleados con limite
            case 'readAllLimit':
                if ($result['dataset'] = $empleados->buscarEmpleadosLimite($_POST['limit'])) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Actualizar contraseña
            case 'actualizarContraL':
                $_POST = $empleados->validateForm($_POST);
                if (!$empleados->setId($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$empleados->checkContrasenaEmpleado($_POST['contrasena_actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                    $result['message'] = $_POST['contrasena_actual'];
                } elseif ($_POST['contrasena_nueva'] != $_POST['contrasena_confirma']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$empleados->setContrasena($_POST['contrasena_nueva'])) {
                    $result['exception'] = $empleados->getPasswordError();
                } elseif ($empleados->cambiarContrasenaEmpleado()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //Leer el perfil
            case 'readProfile':
                if ($result['dataset'] = $empleados->obtenerPerfilEmpleado()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
                break;
                //Buscar empleados
            case 'search':
                $_POST = $empleados->validateForm($_POST);
                if ($_POST['input-file'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $empleados->buscarEmpleadosLimit2($_POST['input-file'],$_POST['limit'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
                //Crear empleado
            case 'create':
                $_POST = $empleados->validateForm($_POST);
                if (!$empleados->setNombre($_POST['nombre-emp'])) {
                    $result['exception'] = 'Nombre incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } else if (!$empleados->setApellido($_POST['apellido-emp'])) {
                    $result['exception'] = 'Apellidoincorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setUsuario($_POST['usuario-emp'])) {
                    $result['exception'] = 'Usuario incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif ($_POST['contra-emp'] != $_POST['contrac-emp']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$empleados->setContrasena($_POST['contra-emp'])) {
                    $result['exception'] = 'Contraseña incorrecta';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!isset($_POST['tipo-de-empleado'])) {
                    $result['exception'] = 'Seleccione un tipo de empleado';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setTipoEmpleado($_POST['tipo-de-empleado'])) {
                    $result['exception'] = 'Tipo de empleado incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setDUI($_POST['dui-emp'])) {
                    $result['exception'] = 'DUI incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setTelefono($_POST['telefono-emp'])) {
                    $result['exception'] = 'Teléfono incorrecto';
                    $result['message'] = $_POST['telefono-emp'];
                } elseif (!$empleados->setCorreo($_POST['correo-emp'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($empleados->crearEmpleado()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado creado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo crear el empleado';
                }
                break;
                //Actualizar empleado
            case 'update':
                $_POST = $empleados->validateForm($_POST);
                if (!$empleados->setId($_POST['id'])) {
                    $result['exception'] = 'Empleado incorrecto';
                    $result['message'] = $_POST['id'];
                } elseif (!$data = $empleados->obtenerEmpleado($_POST['id'])) {
                    $result['exception'] = 'Empleado inexistente';
                } else if (!$empleados->setNombre($_POST['nombre-emp'])) {
                    $result['exception'] = 'Nombre incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } else if (!$empleados->setApellido($_POST['apellido-emp'])) {
                    $result['exception'] = 'Apellidoincorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setUsuario($_POST['usuario-emp'])) {
                    $result['exception'] = 'Usuario incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif ($_POST['contra-emp'] != $_POST['contrac-emp']) {
                    $result['exception'] = 'Claves diferentes';
                } else if (!$data = $empleados->obtenerContra($_POST['id'])) {
                    $result['exception'] = 'Contra inexistente';
                } elseif ($_POST['contra-emp'] == '') {
                    if (!isset($_POST['tipo-de-empleado'])) {
                        $result['exception'] = 'Seleccione un tipo de empleado';
                        $result['message'] = $_POST['nombre-emp'];
                    } elseif (!$empleados->setTipoEmpleado($_POST['tipo-de-empleado'])) {
                        $result['exception'] = 'Tipo de empleado incorrecto';
                        $result['message'] = $_POST['nombre-emp'];
                    } elseif (!$empleados->setDUI($_POST['dui-emp'])) {
                        $result['exception'] = 'DUI incorrecto';
                        $result['message'] = $_POST['nombre-emp'];
                    } elseif (!$empleados->setTelefono($_POST['telefono-emp'])) {
                        $result['exception'] = 'Teléfono incorrecto';
                        $result['message'] = $_POST['telefono-emp'];
                    } elseif (!$empleados->setCorreo($_POST['correo-emp'])) {
                        $result['exception'] = 'Correo incorrecto';
                    } elseif (!$empleados->setEstado(isset($_POST['estado']) ? 4 : 5)) {
                        $result['exception'] = 'Estado de empleado invalido';
                    } elseif ($empleados->actualizarEmpleado()) {
                        $result['status'] = 1;
                        $result['message'] = 'Empleado modificado excepto la contraseña';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No se pudo actualizar el empleado';
                    }
                } elseif (($_POST['contra-emp'] != '' && !$empleados->setContrasena($_POST['contra-emp']))) {
                    $result['exception'] = 'Contra incorrecta';
                } elseif (!isset($_POST['tipo-de-empleado'])) {
                    $result['exception'] = 'Seleccione un tipo de empleado';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setTipoEmpleado($_POST['tipo-de-empleado'])) {
                    $result['exception'] = 'Tipo de empleado incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setDUI($_POST['dui-emp'])) {
                    $result['exception'] = 'DUI incorrecto';
                    $result['message'] = $_POST['nombre-emp'];
                } elseif (!$empleados->setTelefono($_POST['telefono-emp'])) {
                    $result['exception'] = 'Teléfono incorrecto';
                    $result['message'] = $_POST['telefono-emp'];
                } elseif (!$empleados->setCorreo($_POST['correo-emp'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$empleados->setEstado(isset($_POST['estado']) ? 4 : 5)) {
                    $result['exception'] = 'Estado de empleado invalido';
                } elseif ($empleados->actualizarEmpleado()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado modificado con contraseña';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo actualizar el empleado';
                }
                break;
                //Obtener empleado
            case 'obtenerEmpleado':
                if (!$empleados->setId($_POST['id'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif ($result['dataset'] = $empleados->obtenerEmpleado($_POST['id'])) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
                //Eliminar empleado
            case 'delete':
                if ($_POST['id'] == $_SESSION['id_usuario']) {
                    $result['exception'] = 'No se puede eliminar a si mismo';
                } elseif (!$empleados->setId($_POST['id'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$data = $empleados->obtenerEmpleado($_POST['id'])) {
                    $result['exception'] = 'Empleado inexistente';
                } elseif ($empleados->eliminarEmpleado($_POST['id'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado eliminado correctamente';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo eliminar el empleado';
                }
                break;
                //Actualizar el perfil
            case 'updateProf':
                if (!$empleados->setId($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Empleado incorrecto';
                } elseif (!$data = $empleados->obtenerEmpleado($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Empleado inexistente';
                } elseif (!$empleados->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombre invalido';
                } elseif (!$empleados->setUsuario($_POST['Username'])) {
                    $result['exception'] = 'Usuario invalido';
                } elseif (!$empleados->setApellido($_POST['apellido'])) {
                    $result['exception'] = 'Apellido invalido';
                } elseif (!$empleados->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo invalido';
                } elseif (!$empleados->setDUI($_POST['dui'])) {
                    $result['exception'] = 'Dui invalido';
                } elseif (!$empleados->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono invalido';
                } elseif ($empleados->actualizarPerfil()) {
                    $result['status'] = 1;
                    $result['message'] = 'Actualización de perfil correcta';
                    $_SESSION['usuario'] = $empleados->getUsuario();
                    $empleados->nombreApellidoEmpleado();
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No se pudo eliminar el empleado';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
                //Log in
            case 'logIn':
                $_POST = $empleados->validateForm($_POST);
                if (!$empleados->checkUsuarioEmpleado($_POST['usuario'])) {
                    $result['exception'] = 'Nombre de usuario incorrecto';
                } elseif (!$empleados->checkEmpleadosActivos()) {
                    $result['exception'] = 'Nombre de usuario eliminado o bloqueado, comunicate con tu administrador';
                } elseif ($empleados->checkContrasenaEmpleado($_POST['contrasena'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_usuario'] = $empleados->getId();
                    $_SESSION['usuario'] = $empleados->getUsuario();
                    $_SESSION['saludoI'] = false;
                    $empleados->nombreApellidoEmpleado();
                    $empleados->tipoEmpleado();
                } else {
                    $result['exception'] = 'Contraseña incorrecta';
                }
                break;
                //Actualizar la contraseña
            case 'actualizarContra':
                $_POST = $empleados->validateForm($_POST);
                if (!$empleados->checkUsuarioEmpleado($_POST['usuario'])) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$empleados->setContrasena($_POST['contrasena'])) {
                    $result['exception'] = $empleados->getPasswordError();
                } elseif ($empleados->cambiarContrasenaEmpleado()) {
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
