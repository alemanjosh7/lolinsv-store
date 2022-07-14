<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/empresas.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $empresas = new Empresas;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //Leer todo pero con limite
            case 'readAllLimit':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if ($result['dataset'] = $empresas->obtenerEmpresasLimit($_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Empresas encontradas';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay empresas registradas';
                    }
                } else {
                    if ($result['dataset'] = $empresas->obtenerEmpresasAsignadas($_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Empresas encontradas';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay empresas registradas';
                    }
                }
                break;
                //Buscador
            case 'search':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    $_POST = $empresas->validateForm($_POST);
                    if ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif ($result['dataset'] = $empresas->buscarEmpresasAdm($_POST['search'],$_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Valor encontrado';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                } else {
                    $_POST = $empresas->validateForm($_POST);
                    if ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif ($result['dataset'] = $empresas->buscarEmpresaCl($_POST['search'],$_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Valor encontrado';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                }
                break;
                //Crear
            case 'create':
                $_POST = $empresas->validateForm($_POST);
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$empresas->setNombreClt($_POST['nombrecl'])) {
                        $result['exception'] = 'Nombre de cliente invalido';
                    } elseif (!$empresas->setApellidoClt($_POST['apellidocl'])) {
                        $result['exception'] = 'Apellido del cliente invalido';
                    } elseif (!$empresas->setNombreEmp($_POST['nombreemp'])) {
                        $result['exception'] = 'Nombre de empresa invalido';
                    } elseif (!$empresas->setNumeroEmp($_POST['numero'])) {
                        $result['exception'] = 'Número de empresa invalido';
                    } elseif (!$empresas->setCorreoEmp($_POST['correo'])) {
                        $result['exception'] = 'Correo de empresa invalido';
                    } elseif (!$empresas->setDireccionEmp($_POST['direccion'])) {
                        $result['exception'] = 'Direccion de empresa invalido';
                    } elseif (!$empresas->setNitEmp($_POST['nit'])) {
                        $result['exception'] = 'Nit de empresa invalido';
                    } elseif (!$empresas->checkEmpresaName()) {
                        $result['exception'] = 'Ya hay una empresa con ese nombre';
                    } elseif ($empresas->crearEmpresa()) {
                        $result['status'] = 1;
                        $result['message'] = 'Empreza creada';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
                } else {
                    $result['exception'] = '¿Que haces?, tu usuario no puede hacer esto';
                }
                break;
                //Obtener una empresa especifica
            case 'readOne':
                if (!$empresas->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif ($result['dataset'] = $empresas->obtenerEmpresa()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empresa inexistente';
                }
                break;
                //Actualizar
            case 'update':
                $_POST = $empresas->validateForm($_POST);
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$empresas->setId($_POST['id'])) {
                        $result['exception'] = 'Empresa invalida';
                    } elseif (!$empresas->obtenerEmpresa()) {
                        $result['exception'] = 'Empresa inexistente';
                    } elseif (!$empresas->setNombreClt($_POST['nombrecl'])) {
                        $result['exception'] = 'Nombre de cliente invalido';
                    } elseif (!$empresas->setApellidoClt($_POST['apellidocl'])) {
                        $result['exception'] = 'Apellido del cliente invalido';
                    } elseif (!$empresas->setNombreEmp($_POST['nombreemp'])) {
                        $result['exception'] = 'Nombre de empresa invalido';
                    } elseif (!$empresas->setNumeroEmp($_POST['numero'])) {
                        $result['exception'] = 'Número de empresa invalido';
                    } elseif (!$empresas->setCorreoEmp($_POST['correo'])) {
                        $result['exception'] = 'Correo de empresa invalido';
                    } elseif (!$empresas->setDireccionEmp($_POST['direccion'])) {
                        $result['exception'] = 'Direccion de empresa invalido';
                    } elseif (!$empresas->setNitEmp($_POST['nit'])) {
                        $result['exception'] = 'Nit de empresa invalido';
                    } elseif (!$empresas->checkEmpresaAct()) {
                        $result['exception'] = 'Ya hay una empresa con ese nombre';
                    } elseif ($empresas->actualizarEmpresa()) {
                        $result['status'] = 1;
                        $result['message'] = 'Empreza actualizada exitosamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
                } else {
                    $result['exception'] = '¿Que haces?, tu usuario no puede hacer esto';
                }
                //Eliminar
            case 'delete':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$empresas->setId($_POST['id'])) {
                        $result['exception'] = 'Empresa incorrecta';
                    } elseif (!$empresas->obtenerEmpresa()) {
                        $result['exception'] = 'Empresa inexistente';
                    } elseif ($empresas->cambiarEstadoEmp()) {
                        $result['status'] = 1;
                        $result['message'] = 'Empresa eliminada correctamente'; //Strawberry
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = '¿Que haces?, tu usuario no puede hacer esto';
                }
                break;
                //Obtener todas las empresas
            case 'readAll':
                if ($result['dataset'] = $empresas->obtenerEmpresas()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empresas encontradas';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = '¡Lo sentimos! No hay empresas registradas';
                }
                break;
                //Obtener empresas asignadas
            case 'readEmprAsg':
                if ($result['dataset'] = $empresas->obtenerEmpresasAsignCheck($_POST['idemp'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Empresas encontradas';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    //Como no encontramos retornamos un dataset false
                    $result['dataset'] = false;
                }
                break;
                //Obtener empresas para ambos usuario
            case 'readEmprAllUser':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if ($result['dataset'] = $empresas->obtenerEmpresas()) {
                        $result['status'] = 1;
                        $result['message'] = 'Empresas encontradas';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay empresas registradas';
                    }
                } else {
                    if ($result['dataset'] = $empresas->obtenerEmpresasAsignCheck($_SESSION['id_usuario'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Empresas encontradas';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay empresas registradas';
                    }
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
