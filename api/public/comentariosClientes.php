<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/valoracionesCliente.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $valoracioncl = new ValoracionesCliente;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_producto'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAllLimit':
                if ($result['dataset'] = $valoracioncl->mostrarValoracionesLimit($_POST['limit'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Comentarios encontrados';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = '¡Lo sentimos! No hay comentarios registrados para este producto, si ya adquiriste el producto.¡Se el primero!';
                }
                break;
                break;
            case 'comprobarComentario':
                if (isset($_SESSION['id_cliente'])) {
                    $result['session'] = 1;
                    if ($result['dataset'] = $valoracioncl->comprobarCompraCl()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = '¡Lo sentimos! Aun no has comprado este producto, por ende ';
                    }
                } else {
                    $result['exception'] = 'Necesita iniciar sesión para poder realiar un comentario';
                }
                break;
            case 'crearComentario':
                if (isset($_SESSION['id_cliente'])) {
                    $result['session'] = 1;
                    if (isset($_SESSION['id_producto'])) {
                        if (!$valoracioncl->setComentario($_POST['comentario'])) {
                            $result['exception'] = 'Comentario invalido';
                        }elseif (!$valoracioncl->setIdValoracion($_POST['valoracion'])) {
                            $result['exception'] = 'Valoracion invalida';
                        } elseif ($valoracioncl->crearComentarioCl()) {
                            $result['status'] = 1;
                            $result['message'] = 'Comentario subido exitosamente';  
                        } elseif (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = '¡Lo sentimos! No se pudo guardar tu comentario ';
                        }
                    } else {
                        $result['exception'] = 'Producto no seleccionado';
                    }
                } else {
                    $result['exception'] = 'Necesita iniciar sesión para poder realiar un comentario';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        $result['exception'] = 'Necesita seleccionar un producto en la página de productos';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
