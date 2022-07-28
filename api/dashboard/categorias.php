<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/categorias.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $categorias = new Categorias;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $categorias->readCategories()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readAllLimit':
                if ($result['dataset'] = $categorias->limit($_POST['limit'])) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay categorias registradas';
                }
                break;
            case 'search':
                $_POST = $categorias->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $categorias->searchCategory($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'create':
                $_POST = $categorias->validateForm($_POST);
                if (!$categorias->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif ($categorias->createCategory()) {
                    $result['status'] = 1;
                    $result['message'] = 'Categoría creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'update':
                $_POST = $categorias->validateForm($_POST);
                if (!$categorias->setId($_POST['id_categoria'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif (!$categorias->readACategory()) {
                    $result['exception'] = 'Cliente inexistente';
                } elseif (!$categorias->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif ($categorias->updateCategory()) {
                    $result['status'] = 1;
                    $result['message'] = 'Categoria modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readACategory':
                if (!$categorias->setId($_POST['id_categoria'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif ($result['dataset'] = $categorias->readACategory()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Categoría inexistente';
                }
                break;
            case 'delete':
                if (!$categorias->setId($_POST['id_categoria'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif (!$data = $categorias->readACategory()) {
                    $result['exception'] = 'Categoría inexistente';
                } elseif ($categorias->deleteCategory()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = Database::getException();
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

//($_POST['limit']))