<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/clientes.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $clientes = new Clientes;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_clientes'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    }else {
        switch ($_GET['action']){
            case 'readUsers':
            if($clientes->obtenerClientes()){
                $result['status'] = 1;
                $result['message'] = 'Existen';
                }else {
                    $result['exeption'] = 'No existen clientes registrados';
                }
                break;
                case 'logIn':
                    $_POST = $clientes->validateForm($_POST);
                    if (!$clientes->checkUsuarioCl($_POST['usuario'])) {
                        $result['exception'] = 'Nombre de usuario incorrecto';
                    } elseif ($clientes->checkContrasenaCl($_POST['contrasena'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Autenticación correcta';
                        $_SESSION['id_cliente'] = $clientes->getId();
                        $_SESSION['usuario'] = $clientes->getUsuario();
                        $_SESSION['saludoI'] = false;
                        $clientes->nombreApellidoAdminCl();
                    }else {
                        $result['exception'] = 'Contraseña incorrecta';
                    }
                    break;
            }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
