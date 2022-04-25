<?php
require_once('../helpers/database.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $variablesgb = new Variablesgb;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = null;
    switch($_GET['action']){
        case 'getIdUsuario':
            $_SESSION['idUsuriogb'] = $_SESSION['id_admin']
            $result = $_SESSION['idUsuriogb'];
            break;
        case 'setIdUsuario':
            $_SESSION['id_admin'] = $_POST['id'] 
            break;
        case 'setNombreUsuario':
            $_SESSION['nombreUsuario'] = $_POST['nombre']
            break;
        case 'setApellidoUsuario':
            $_SESSION['apellidoUsuario'] = $_POST['apellido']
            break;
        
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));   
} else {
    print(json_encode('Recurso no disponible'));
}
