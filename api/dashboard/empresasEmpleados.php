<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/empresasEmpleados.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $empresas = new EmpresasEmpleados;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'create':
                $_POST = $empresas->validateForm($_POST);
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$empresas->setIdEmpresaEmpresa($_POST['id'])) {
                        $result['exception'] = 'Id de empresa incorrecto';
                    } elseif (!$empresas->setIdEmpresaEmpleados($_POST['idemp'])) {
                        $result['exception'] = 'Id de empleado incorrecto';
                    } elseif (!$empresas->obtenerEmpresa($_POST['id'])) {
                        $result['exception'] = 'La empresa ya no existe';
                    } elseif (!$empresas->obtenerEmpleado($_POST['idemp'])) {
                        $result['exception'] = 'El usuario no fue encontrado';
                    } elseif ($empresas->crearEmpresaEmpleado()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No se pudo dar el acceso a la empresa seleccionada';
                    }
                    break;
                } else {
                    $result['exception'] = '¿Que haces?, tu usuario no puede hacer esto';
                }
                break;
                //Obtener una empresa especifica
            case 'readOne':
                if (!$empresas->setIdEmpresasEmpleados($_POST['id'])) {
                    $result['exception'] = 'Id de enlace incorrecto';
                } elseif ($result['dataset'] = $empresas->obtenerRegistro()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Enlace inexistente';
                }
                break;
                //Eliminar
            case 'delete':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$empresas->setIdEmpresaEmpresa($_POST['id'])) {
                        $result['exception'] = 'Id de empresa incorrecto';
                    } elseif (!$empresas->setIdEmpresaEmpleados($_POST['idemp'])) {
                        $result['exception'] = 'Id de empleado incorrecto';
                    } elseif ($empresas->eliminarConexionEmpEmpr()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No se pudo eliminar el acceso a la empresa';
                    }
                } else {
                    $result['exception'] = '¿Que haces?, tu usuario no puede hacer esto';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        $result['exception'] = 'Necesita loguearse';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
