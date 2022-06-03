<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/clientes.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new Clientes;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['id_cliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getUser':
                if (isset($_SESSION['correo_cliente'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['correo_cliente'];
                } else {
                    $result['exception'] = 'Correo de usuario indefinido';
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
                    if ($result['dataset'] = $cliente->obtenerPerfil()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
            break;
            case 'update':
                $_POST = $cliente->validateForm($_POST);
                if (!$cliente->setId($_SESSION['id_cliente'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif (!$cliente->obtenerCliente()) {
                    $result['exception'] = 'Cliente inexistente';
                } elseif (!$cliente->setNombre($_POST['nombre_usuario-perfil'])) {
                    $result['exception'] = 'Nombres invalido';
                } elseif (!$cliente->setApellido($_POST['apellido_usuario-perfil'])) {
                    $result['exception'] = 'Apellidos invalido';
                } elseif (!$cliente->setCorreo($_POST['e-mail'])) {
                    $result['exception'] = 'Correo invalido';
                } elseif (!$cliente->setDUI($_POST['dui_usuario-perfil'])) {
                    $result['exception'] = 'DUI invalido';
                } elseif (!$cliente->setTelefono($_POST['telefono_usuario-perfil'])) {
                    $result['exception'] = 'Telefono invalido';
                } elseif (!$cliente->setUsuario($_POST['Username'])) {
                    $result['exception'] = 'Usuario invalido';
                } elseif (!$cliente->setDireccion($_POST['direccion_usuario-perfil'])) {
                    $result['exception'] = 'Direccion invalida';
                } elseif (!$cliente->setEstado(8)) {
                    $result['exception'] = 'DUI invalido';
                } elseif ($cliente->actualizarCliente()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                case 'changePassword':
                    $_POST = $cliente->validateForm($_POST);
                    if (!$cliente->setId($_SESSION['id_cliente'])) {
                        $result['exception'] = 'Admin incorrecto';
                    } elseif (!$cliente->checkContrasenaCl($_POST['contraseña_actual'])) {
                        $result['exception'] = 'Clave actual incorrecta';
                        $result['message'] = $_POST['contraseña_actual'];
                    } elseif ($_POST['contraseña_nueva'] != $_POST['contraseña_confirma']) {
                        $result['exception'] = 'Claves nuevas diferentes';
                    } elseif (!$cliente->setContrasena($_POST['contraseña_nueva'])) {
                        $result['exception'] = $cliente->getPasswordError();
                    } elseif ($cliente->cambiarContrasenaCl()) {
                        $result['status'] = 1;
                        $result['message'] = 'Contraseña cambiada correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'register':
                $_POST = $cliente->validateForm($_POST);
                $secretKey = '6LdBzLQUAAAAAL6oP4xpgMao-SmEkmRCpoLBLri-';
                $ip = $_SERVER['REMOTE_ADDR'];

                $data = array('secret' => $secretKey, 'response' => $_POST['g-recaptcha-response'],'remoteip' => $ip);

                $options = array(
                    'http' => array('header'  => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($data)),
                    'ssl' => array('verify_peer' => false, 'verify_peer_name' => false)
                );

                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $context  = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                $captcha = json_decode($response, true);

                if (!$captcha['success']) {
                    $result['recaptcha'] = 1;
                    $result['exception'] = 'No eres un humano';
                } elseif (!$cliente->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$cliente->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$cliente->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$cliente->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Dirección incorrecta';
                } elseif (!$cliente->setDUI($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } elseif (!$cliente->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Fecha de nacimiento incorrecta';
                } elseif (!$cliente->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Teléfono incorrecto';
                } elseif ($_POST['clave'] != $_POST['confirmar_clave']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$cliente->setClave($_POST['clave'])) {
                    $result['exception'] = $cliente->getPasswordError();
                } elseif ($cliente->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'logIn':
                $_POST = $cliente->validateForm($_POST);
                if (!$cliente->checkUser($_POST['usuario'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$cliente->getEstado()) {
                    $result['exception'] = 'La cuenta ha sido desactivada';
                } elseif ($cliente->checkPassword($_POST['clave'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_cliente'] = $cliente->getId();
                    $_SESSION['correo_cliente'] = $cliente->getCorreo();
                } else {
                    $result['exception'] = 'Clave incorrecta';
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
