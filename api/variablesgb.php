<?php
require_once('../helpers/database.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $variablesgb = new Variablesgb;
    switch($_GET['action']){
        case 'getIdUsuario':
            $_SESSION['idUsuriogb'] = $_SESSION['id_admin']
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
} else {
    print(json_encode('Recurso no disponible'));
}
