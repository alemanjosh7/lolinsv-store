<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libraries/PHPMailer/src/Exception.php';
require 'libraries/PHPMailer/src/PHPMailer.php';
require 'libraries/PHPMailer/src/SMTP.php';

// Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
session_start();

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$result = array('message' => null, 'exception' => null, 'status' => null);


//Declaramos algunas variables
$cliente = $_POST['nombre'].''.$_POST['apellido'];
$usuario = $_SESSION['usuario'];
$codigo =$_POST['id'];
$total = $_POST['total'];
$correo = $_POST['correo'];
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8" />
    <title>Lolin.sv-Gracias por la compra</title>
</head>

<body class=" grey lighten-3" style="margin: 0; padding:0;">
    <div>
        <nav class="orange darken-4 center">
            <div class="nav-wrapper">
                <h3 style="margin: 0;">Lolin.sv</h3>
            </div>
        </nav>
    </div>
    <div class="container">
        <h1 class="center-align">¡Gracias por tu compra!</h1>
        <p>Muchas gracias por tu reciente compra en nuestro sitio web de Lolin, esperamos llegue tu pedido pronto, en todo caso nos contactaremos contigo, tu compra ha sido de:<b> $'.$total.'</b>. Con el número de facturación de:</p>
        <div class="row center">
            <div class="col s12 m6  offset-l3 offset-m3">
                <div class="card-panel orange darken-4 white-text">
                    <h3>'.$codigo.'</h3>
                </div>
            </div>
        </div>
    </div>
    <!--JavaScript al final para optimizar-->
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>

</html>';

$mensaje = wordwrap($body, 70, "\r\n");

try {
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'lolinsvoficial@gmail.com';                     //SMTP username
    $mail->Password   = 'lolincontra';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('lolinsvoficial@gmail.com', 'Lolinsv-Oficial');
    $mail->addAddress($correo, $cliente);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Gracias por tu compra';
    $mail->Body    = $mensaje;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->CharSet  = 'utf-8';
    $mail->send();
    $result['status'] = 1;
    $result['message'] = 'Mensaje enviado correctamente';
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result)); 
} catch (Exception $e) {
    $result['exception'] = 'El correo no se pudo enviar';
    print(json_encode($result)); 
}
?>