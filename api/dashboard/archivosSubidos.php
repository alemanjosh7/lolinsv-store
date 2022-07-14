<?php

require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/archivosSubidosEmp.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $archivos = new ArchivosSubidosEmp;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //Leer todo pero con limite
            case 'readAllLimit':
                //Comprobamos si es administrador para cargar todos los archivos o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if ($result['dataset'] = $archivos->obtenerArchivoLimit($_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Archivos encontrados';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay archivos subidos por los empleados';
                    }
                } else {
                    //Como no es administrador se cargan los datos para el empleado
                    if (!$archivos->setIdEmpleado($_SESSION['id_usuario'])) {
                        $result['exception'] = 'Usuario invalido';
                    } elseif ($result['dataset'] = $archivos->obtenerArchivoLimitEmp($_POST['limit'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Archivos encontrados';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! No hay archivos subidos por ti';
                    }
                }
                break;
                //Buscardor
            case 'search':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                $_POST = $archivos->validateForm($_POST);
                //Comprobamos si es administrador para cargar todos los archivos o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if ($_POST['search'] == '') {
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
                    if ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif (!$archivos->setIdEmpleado($_SESSION['id_usuario'])) {
                        $result['exception'] = 'Usuario invalido';
                    } elseif ($result['dataset'] = $archivos->buscarArchivosEMP($_POST['search'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Valor encontrado';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                }
                break;
                //Buscador con filtro
            case 'searchFilter':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                $_POST = $archivos->validateForm($_POST);
                //Comprobamos si es administrador para cargar todos los archivos o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif (!$archivos->setIdEmpresa($_POST['filter'])) {
                        $result['exception'] = 'Empresa invalida';
                    } elseif ($result['dataset'] = $archivos->buscarArchivosFilter($_POST['search'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Valor encontrado';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                } else {
                    if ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif (!$archivos->setIdEmpleado($_SESSION['id_usuario'])) {
                        $result['exception'] = 'Usuario invalido';
                    } elseif (!$archivos->setIdEmpresa($_POST['filter'])) {
                        $result['exception'] = 'Empresa invalida';
                    } elseif ($result['dataset'] = $archivos->buscarArchivosFilterEMP($_POST['search'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Valor encontrado';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                }
                break;
                //Obtener los archivos con limite y filtro
            case 'searchFilter1':
                //Comprobamos si es administrador para cargar todas las empresas o solo seleccionadas
                $_POST = $archivos->validateForm($_POST);
                //Comprobamos si es administrador para cargar todos los archivos o solo seleccionadas
                if ($_SESSION['tipo_usuario'] == 4) {
                    if (!$archivos->setIdEmpresa($_POST['filter'])) {
                        $result['exception'] = 'Empresa invalida';
                    } elseif ($result['dataset'] = $archivos->obtenerArchivosFilter()) {
                        $result['status'] = 1;
                        $result['message'] = 'Archivos encontrados';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay archivos para esta empresa';
                    }
                } else {
                    if (!$archivos->setIdEmpleado($_SESSION['id_usuario'])) {
                        $result['exception'] = 'Usuario invalido';
                    } elseif (!$archivos->setIdEmpresa($_POST['filter'])) {
                        $result['exception'] = 'Empresa invalida';
                    } elseif ($result['dataset'] = $archivos->obtenerArchivosFilterEMP()) {
                        $result['status'] = 1;
                        $result['message'] = 'Archivos encontrados';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay archivos para esta empresa que hallas subido';
                    }
                }
                break;
                //Crear
            case 'create':
                $_POST = $archivos->validateForm($_POST);
                if (!isset($_POST['empresa'])) {
                    $result['exception'] = 'Seleccione la empresa a la que pertenece el archivo';
                } elseif (!$archivos->setIdEmpresa($_POST['empresa'])) {
                    $result['exception'] = 'Empresa invalida';
                } elseif (!$archivos->setIdEmpleado($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Empleado invalido';
                } elseif (!$archivos->setDescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif (!$archivos->setTamano($_FILES['archivo']['size'])) {
                    $result['exception'] = 'Ocurrio un error grave al medir el tamaño de los archivos';
                    //$result['exception'] = $_FILES['archivo']['size'];
                } elseif (!$archivos->setNombreOriginal($_POST['nombre'])) {
                    $result['exception'] = 'Nombre del archivo incorrecto';
                } elseif (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                    $result['exception'] = 'Seleccione un archivo';
                } elseif (!$archivos->setNombreArchivo($_FILES['archivo'])) {
                    $result['exception'] = $archivos->getFileError();
                } elseif ($archivos->crearArchivoEmp()) {
                    $result['status'] = 1;
                    if ($archivos->saveFile($_FILES['archivo'], $archivos->getRoute(), $archivos->getNombreArchivo())) {
                        $result['message'] = 'Archivo subido correctamente';
                    } else {
                        $result['message'] = 'Archivo subido pero no se guardó el archivo en el servidor';
                    }
                } else {
                    $result['exception'] = 'No se ha podido crear el archivo';
                }
                break;
                //Obtener un archivo especifico
            case 'readOne':
                if (!$archivos->setIdArchSubidosEmp($_POST['id'])) {
                    $result['exception'] = 'Archivo invalido';
                } elseif ($result['dataset'] = $archivos->obtenerArchivo()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Archivo inexistente';
                }
                break;
                //Eliminar un archivo
            case 'delete':
                if (!$archivos->setIdArchSubidosEmp($_POST['id'])) {
                    $result['exception'] = 'Registro incorrecto';
                } elseif (!$data = $archivos->setIdEmpleado($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$data = $archivos->obtenerArchivo()) {
                    $result['exception'] = 'Producto inexistente';
                } elseif ($archivos->eliminarArchivoEmp()) {
                    $result['status'] = 1;
                    if ($archivos->deleteFile($archivos->getRoute(), $data['nombre_archivo'])) {
                        $result['message'] = 'Archivo eliminado correctamente';
                    } else {
                        $result['message'] = 'Archivo eliminado pero no se borró la imagen';
                    }
                } else {
                    $result['exception'] = 'No se ha podido eliminar el archivo';
                }
                break;
                //Actualizar a descargado para un archivo
            case 'estadoDesc':
                if (!$archivos->setIdArchSubidosEmp($_POST['id'])) {
                    $result['exception'] = 'Registro incorrecto';
                } elseif (!$data = $archivos->setIdEmpleado($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Usuario inexistente';
                } elseif (!$data = $archivos->obtenerArchivo()) {
                    $result['exception'] = 'Producto inexistente';
                } elseif ($archivos->estadoDesc()) {
                    $result['status'] = 1;
                    $result['message'] = 'Se ha cambiado su estado';
                } else {
                    $result['exception'] = 'No se ha podido actualizar su estado';
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
