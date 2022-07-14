<?php

require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/folders.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $folders = new Folders;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //Leer todo pero con limite
            case 'readAllLimit':
                //Comprobamos que halla seleccionado una empresa
                if (isset($_SESSION['id_empresa'])) {
                    if (!$folders->setIdEmpresa($_SESSION['id_empresa'])) {
                        $result['exception'] = 'Empresa incorrecta';
                    } elseif ($result['dataset'] = $folders->obtenerFoldersLimit($_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Empresas encontrados';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay folders registrados para esta empresa';
                    }
                } else {
                    $result['exception'] = '¡Lo sentimos! Debe seleccionar una empresa';
                }
                break;
                //Buscador
            case 'search':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if (isset($_SESSION['id_empresa'])) {
                    $_POST = $folders->validateForm($_POST);
                    if (!$folders->setIdEmpresa($_SESSION['id_empresa'])) {
                        $result['exception'] = 'Empresa incorrecta';
                    } elseif ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif ($result['dataset'] = $folders->buscarFolders($_POST['search'], $_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Valor encontrado';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                } else {
                    $result['exception'] = '¡Lo sentimos! Debe seleccionar una empresa';
                }
                break;
                //Crear
            case 'create':
                $_POST = $folders->validateForm($_POST);
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$folders->setIdEmpresa($_SESSION['id_empresa'])) {
                        $result['exception'] = 'Empresa incorrecta';
                    } elseif (!$folders->setNombreFol($_POST['nombre'])) {
                        $result['exception'] = 'Nombre del folder invalido';
                    } elseif (!$folders->checkFolderName()) {
                        $result['exception'] = 'Ya hay un folder con ese nombre en esta empresa';
                    } elseif ($folders->crearFolder()) {
                        $result['status'] = 1;
                        $result['message'] = 'Folder creado';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {

                    $result['exception'] = '¿Que haces?. Tu usuario no puede hacer esto';
                }
                break;
                //Obtener una empresa especifica
            case 'readOne':
                if (!$folders->setIdEmpresa($_SESSION['id_empresa'])) {
                    $result['exception'] = 'Empresa incorrecta';
                } elseif (!$folders->setId($_POST['id'])) {
                    $result['exception'] = 'Folder incorrecto';
                } elseif ($result['dataset'] = $folders->obtenerFolder()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Folder inexistente';
                }
                break;
                //Actualizar
            case 'update':
                $_POST = $folders->validateForm($_POST);
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$folders->setIdEmpresa($_SESSION['id_empresa'])) {
                        $result['exception'] = 'Empresa incorrecta';
                    } elseif (!$folders->setId($_POST['id'])) {
                        $result['exception'] = 'Folder incorrecto';
                    } elseif (!$folders->obtenerFolder()) {
                        $result['exception'] = 'Folder inexistente';
                    } elseif (!$folders->setNombreFol($_POST['nombre'])) {
                        $result['exception'] = 'Nombre de folder invalido';
                    } elseif (!$folders->checkFolderNameAct()) {
                        $result['exception'] = 'Ya hay un folder con ese nombre en esta empresa';
                    } elseif ($folders->actualizarFolder()) {
                        $result['status'] = 1;
                        $result['message'] = 'Folder actualizado exitosamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = '¿Que haces?. Tu usuario no puede hacer esto';
                }
                break;
                //Eliminar
            case 'delete':
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$folders->setIdEmpresa($_SESSION['id_empresa'])) {
                        $result['exception'] = 'Empresa incorrecta';
                    } elseif (!$folders->setId($_POST['id'])) {
                        $result['exception'] = 'Folder incorrecta';
                    } elseif (!$folders->obtenerFolder()) {
                        $result['exception'] = 'Folder inexistente';
                    } elseif ($folders->cambiarEstadoFol()) {
                        $result['status'] = 1;
                        $result['message'] = 'Folder eliminado correctamente'; //Strawberry
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = '¿Que haces?. Tu usuario no puede hacer esto';
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
