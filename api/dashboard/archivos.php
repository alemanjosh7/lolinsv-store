<?php

require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/archivos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $archivos = new Archivos;
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
                if (isset($_SESSION['id_folder'])) {
                    if (!$archivos->setIdFolder($_SESSION['id_folder'])) {
                        $result['exception'] = 'Folder incorrecto';
                    } elseif ($result['dataset'] = $archivos->obtenerArchivoLimit($_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Archivos encontrados';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay archivos registrados para este folder';
                    }
                } else {
                    $result['exception'] = '¡Lo sentimos! Debe seleccionar un folder';
                }
                break;
                //Buscardor
            case 'search':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if (isset($_SESSION['id_folder'])) {
                    $_POST = $archivos->validateForm($_POST);
                    if (!$archivos->setIdFolder($_SESSION['id_folder'])) {
                        $result['exception'] = 'Folder incorrecto';
                    } elseif ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif ($result['dataset'] = $archivos->buscarArchivos($_POST['search'])) {
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
                //Obtener el nombre de la empresa y folder seleccionados
            case 'obtenerEmpFol':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                if (isset($_SESSION['id_folder'])) {
                    if ($result['dataset'] = $archivos->nombreEmpFol()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'No se pudo encontrar el nombre del folder y la empresa selecionada';
                    }
                } else {
                    $result['exception'] = '¡Lo sentimos! Debe seleccionar una empresa';
                }
                break;
                //Crear
            case 'create':
                $_POST = $archivos->validateForm($_POST);
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$archivos->setIdFolder($_SESSION['id_folder'])) {
                        $result['exception'] = 'Folder incorrecto';
                    } elseif (!$archivos->setTamano($_FILES['archivo']['size'])) {
                        $result['exception'] = 'Ocurrio un error grave al medir el tamaño de los archivos';
                        //$result['exception'] = $_FILES['archivo']['size'];
                    } elseif (!$archivos->setOriginal($_POST['nombre'])) {
                        $result['exception'] = 'Nombre del archivo incorrecto';
                    } elseif (!$archivos->checkArchivo()) {
                        $result['exception'] = 'Ya hay un archivo con ese nombre en este folder';
                    } elseif (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                        $result['exception'] = 'Seleccione un archivo';
                    } elseif (!$archivos->setNombreArch($_FILES['archivo'])) {
                        $result['exception'] = $archivos->getFileError();
                    } elseif ($archivos->crearArchivo()) {
                        $result['status'] = 1;
                        if ($archivos->saveFile($_FILES['archivo'], $archivos->getRoute(), $archivos->getNombreArch())) {
                            $result['message'] = 'Archivo creado correctamente';
                        } else {
                            $result['message'] = 'Archivo creado pero no se guardó el archivo en el servidor';
                        }
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {

                    $result['exception'] = '¿Que haces?. Tu usuario no puede hacer esto';
                }
                break;
                //Obtener un archivo especifico
            case 'readOne':
                if (!$archivos->setIdFolder($_SESSION['id_folder'])) {
                    $result['exception'] = 'Folder incorrecto';
                } elseif (!$archivos->setId($_POST['id'])) {
                    $result['exception'] = 'Archivo incorrecto';
                } elseif ($result['dataset'] = $archivos->obtenerArch()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Archivo inexistente';
                }
                break;
                //Actualizar
            case 'update':
                $_POST = $archivos->validateForm($_POST);
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$archivos->setId($_POST['id'])) {
                        $result['exception'] = 'Archivo incorrecto';
                    } elseif (!$archivos->setIdFolder($_SESSION['id_folder'])) {
                        $result['exception'] = 'Folder inexistente';
                    } elseif (!$data = $archivos->obtenerArch()) {
                        $result['exception'] = 'Archivo inexistente';
                    } elseif (!$archivos->setOriginal($_POST['nombre'])) {
                        $result['exception'] = 'Nombre del archivo incorrecto';
                    } elseif (!$archivos->checkArchivoACT()) {
                        $result['exception'] = 'Ya hay un archivo con ese nombre en este folder';
                    } elseif (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                        if ($archivos->actualizarArchivo($data['nombre_archivo'], $data['tamano'], $data['fecha_subida'])) {
                            $result['status'] = 1;
                            $result['message'] = 'Archivo modificado correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } elseif (!$archivos->setFecha($_POST['fecha'])) {
                        $result['exception'] = 'Fecha de actualización incorrecta';
                    } elseif (!$archivos->setTamano($_FILES['archivo']['size'])) {
                        $result['exception'] = 'Ocurrio un error grave al medir el tamaño de los archivos';
                        //$result['exception'] = $_FILES['archivo']['size'];
                    } elseif (!$archivos->setNombreArch($_FILES['archivo'])) {
                        $result['exception'] = $archivos->getFileError();
                    } elseif ($archivos->actualizarArchivo($data['nombre_archivo'], $data['tamano'], $data['fecha_subida'])) {
                        $result['status'] = 1;
                        if ($archivos->saveFile($_FILES['archivo'], $archivos->getRoute(), $archivos->getNombreArch())) {
                            $result['message'] = 'Archivo modificado correctamente';
                        } else {
                            $result['message'] = 'Archivo modificado pero no se guardó el archivo';
                        }
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
                    if (!$archivos->setIdFolder($_SESSION['id_folder'])) {
                        $result['exception'] = 'Empresa incorrecta';
                    } elseif (!$archivos->setId($_POST['id'])) {
                        $result['exception'] = 'Archivo incorrecto';
                    } elseif (!$data = $archivos->obtenerArch()) {
                        $result['exception'] = 'Folder inexistente';
                    } elseif ($archivos->cambiarEstadoArch()) {
                        $result['status'] = 1;
                        $nuevaUbicacion = '../documents/archivosBorrados/'.$data['nombre_archivo'];
                        $ubicacionActual = '../documents/archivosFolders/'.$data['nombre_archivo'];
                        if(rename($ubicacionActual,$nuevaUbicacion)){
                           $result['message'] = 'Archivo eliminado correctamente'; 
                        }else{
                            $result['message'] = 'Archivo eliminado correctamente pero no se pudo hacer respaldo'; 
                        }
                        
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
