 <?php
/*Esta carpeta indicará las variables globales de sesion y tendrá algunos metodos para obtenerlos, por el momento solo los definiremos
id_usuario = El id del administrador 
usuario = EL nombre del usuario 
nombreUsuario = Nombrel de la persona con el id_usuario
apellidoUsuario = Apellido de la persona con el id_usuario
id_cliente = El id del cliente
id_pedidoEsta = El id del pedido establecido
id_producto = El id del producto
*/
require_once('helpers/database.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    /*$_SESSION['id_cliente'] = 1;
    $_SESSION['id_pedidoEsta'] = 1;
    $_SESSION['usuario'] = 'Fuentesc82';*/
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'idusuario' => null, 'usuario' => null,
    'nombre' => null, 'apellido' => null, 'id_pedidoEsta' => null, 'id_producto' => null);
    switch($_GET['action']){
        case 'getIdUsuario':
            $result['status'] = 1;
            $result['idusuario'] = $_SESSION['id_usuario'];
            break;
        case 'setIdUsuario':
            $result['status'] = 1;
            $_SESSION['id_usuario'] = $_POST['id']; 
            break;
        case 'setNombreUsuario':
            $result['status'] = 1;
            $_SESSION['nombreUsuario'] = $_POST['nombre'];
            break;
        case 'setApellidoUsuario':
            $result['status'] = 1;
            $_SESSION['apellidoUsuario'] = $_POST['apellido'];
            break;
        case 'comprobarPINLog':
            $var = $_POST['pin'];
            $pin = 'Root1L';
            if($var == $pin){
                $result['status'] = 1;
                $result['message'] = 'PIN correcto';
            }else{
                $result['exception'] = 'PIN incorrecto solicite ayuda del administrador en jefe';
            }
            break;
        case 'getNombreApellido':
                $result['status'] = 1;
                $result['nombre'] = $_SESSION['nombreUsuario'];
                $result['apellido'] = $_SESSION['apellidoUsuario'];
            break;
        //Caso para verificar el saludo al pasar el login 
        case 'verificarSaludoI':
            //Si el saludo no es true
            if($_SESSION['saludoI']!=true){
                //Se envia una comprobación que no es verdadero y se envia el nombre y el apellido del usuario
                //Adicionalmente se cambia su valor a true
                $result['status'] = 1;
                $result['nombre'] = $_SESSION['nombreUsuario'];
                $result['apellido'] = $_SESSION['apellidoUsuario'];
                $_SESSION['saludoI'] = true; 
            }else{
                //Como no es verdadero, solo se envia el nombre y el apellido del usuario
                $result['nombre'] = $_SESSION['nombreUsuario'];
                $result['apellido'] = $_SESSION['apellidoUsuario']; 
            }
            break;
        //Caso para verificar si hay una sesión iniciada por parte del cliente
        case 'verificarCLLog':
            //Se comprueba si se ha inicializado la variable de session id_cliente
            if(isset($_SESSION['id_cliente'])){
                //Si lo esta se comprueba y se envia su usuario
                $result['status'] = 1;
                $result['usuario'] = $_SESSION['usuario'];
            }else{
                //Si no lo esta se envia un error
                $result['exception'] = 'El cliente no ha iniciado session';
            }
            break;
        case 'getIdPedido':
            $result['status'] = 1;
            $result['id_pedidoEsta'] = $_SESSION['id_pedidoEsta'];
            break;
        case 'getIdProducto':
            $result['status'] = 1;
            $result['id_producto'] = $_SESSION['id_producto'];
            break;
        case 'setIdProducto':
            $result['status'] = 1;
            $result['id_producto'] = $_POST['id_producto'];
            break;
        //Caso para verificar si hay un carrito que cargar
        case 'verificarCarrito':
            //Se compureba si se ha inicializado la variable de session id_pedidoEsta
            if(isset($_SESSION['id_pedidoEsta'])){
                //Si lo esta se comprueba y se envia el id
                $result['status'] = 1;
                $result['id_pedidoEsta'] = $_SESSION['id_pedidoEsta'];
            }else{
                //Si no lo esta se envia un error
                $result['exception'] = 'No hay un id de pedido establecido declarado';
            }
            break;
        default:
            $result['exception'] = 'Acción no disponible dentro de la sesión';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));   
} else {
    print(json_encode('Recurso no disponible'));
}
?>