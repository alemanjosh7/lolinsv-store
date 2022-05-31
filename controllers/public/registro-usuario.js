// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_REG = SERVER + 'public/registroUsuario.php?action=';
const API_CLIENTES = SERVER + 'public/login.php?action=';

//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    //Inicializamos todos los componentes de materialize
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    M.Modal.init(document.querySelectorAll('.modal'));
    //Comprobamos que no halla una sesión
    //comprobaClientes();
    //Evitar que se pueda copiar y pegar en algunos componentes
    let arreglo = [DUIINPUT, TELFINPUT, CONTRAN, CONTRAC];//Arreglo donde se encontraran los componentes
    //Ejecutamos los metodos
    noCopy(arreglo);
    noPaste(arreglo);
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
/*Declaramos algunos componentes*/
const OJO = document.getElementById('ocultarmostrar_contraseñas');//Ojo para ocultar y mostrar contraseñas
const CONTRAN = document.getElementById('contrasena_usuario');//input de la contraseña nueva en restablecer contraseña
const CONTRAC = document.getElementById('confirmar');//input de la confirmación de la contraseña en restablecer contraseña
const MENSAJE = document.getElementById('mensaje-contra');//mensaje para las contraseñas
const DUIINPUT = document.getElementById('dui_usuario');//input del dui
const TELFINPUT = document.getElementById('telefono_usuario');//input del telefono
const NOMBREINPUT = document.getElementById('nombre_cliente');//input del nombre del cliente
const APELLIDOINPUT = document.getElementById('apellido_usuario');//input del apellido del cliente
//Metodo para ocultrar y mostrar contraseñas
OJO.addEventListener('click', function () {
    if (CONTRAN.type == "password") {
        CONTRAN.type = "text";
        CONTRAC.type = "text";
        OJO.innerText = "visibility_off";
    } else {
        CONTRAN.type = "password";
        CONTRAC.type = "password"
        OJO.innerText = "visibility";
    }
});

//Metodo para validar que las contraseñas sean iguales mientras se escribe
//Función para comprobar si las contraseñas son iguales
function contrasenasIguales() {
    if (CONTRAN.value != CONTRAC.value) {
        MENSAJE.innerText = 'Las contraseñas no coinciden';
        MENSAJE.style.display = 'block';
    } else if (CONTRAN.value.length < 6) {
        MENSAJE.innerText = 'Las contraseñas deben tener más de 6 caracteres';
        MENSAJE.style.display = 'block';
    }
    else {
        MENSAJE.style.display = 'none';
    }
}
//Funciónes para comprobar las contraseñas mientras estan siendo escritas
CONTRAN.addEventListener('keyup', function () {
    contrasenasIguales();
});
CONTRAC.addEventListener('keyup', function () {
    contrasenasIguales();
});

//Metodos para validar que se escriba en el formato correcto dentro de los inputs

//Validar solo letras en los campos de Nombre y Apellido del cliente
NOMBREINPUT.addEventListener('keypress', function (e) {
    if (!soloLetras(event, 1)) {
        e.preventDefault();
    }
});

APELLIDOINPUT.addEventListener('keypress', function (e) {
    if (!soloLetras(event, 1)) {
        e.preventDefault();
    }
});

//Validar guion dui en el campo de dui y solo numeros Y telefono además de permitir solo números
DUIINPUT.addEventListener('keypress', function (e) {
    if (!soloNumeros(event, 2)) {
        e.preventDefault();
    }
});

TELFINPUT.addEventListener('keypress', function (e) {
    if (!soloNumeros(event, 2)) {
        e.preventDefault();
    }
});

DUIINPUT.addEventListener('keyup', function (e) {
    guionDUI(e, DUIINPUT);
});

TELFINPUT.addEventListener('keyup', function (e) {
    guionTelefono(e, TELFINPUT);
});

// Función para obtener un token del reCAPTCHA y asignarlo al formulario.
/*function reCAPTCHA() {
    // Método para generar el token del reCAPTCHA.
    grecaptcha.ready(function () {
        // Se declara e inicializa una variable para guardar la llave pública del reCAPTCHA.
        let publicKey = '6LdBzLQUAAAAAJvH-aCUUJgliLOjLcmrHN06RFXT';
        // Se obtiene un token para la página web mediante la llave pública.
        grecaptcha.execute(publicKey, { action: 'homepage' }).then(function (token) {
            // Se asigna el valor del token al campo oculto del formulario
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
}*/

//Función para verificar si hay una sesion
// Petición para consultar si existen usuarios registrados.
function comprobaClientes() {
    fetch(API_CLIENTES + 'readUsers', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {
                    location.href = 'index.html';
                }
                else {
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}
//// Método manejador de eventos que se ejecuta cuando se envía el formulario de registrar cliente.
document.getElementById('registro-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    //Se comprueba si el mensaje de las contraseñas se esta mostrando
    if (MENSAJE.style.display == 'none') {
        // Petición para registrar un usuario como cliente.
        fetch(API_REG + 'register', {
            method: 'post',
            body: new FormData(document.getElementById('registro-form'))
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                // Se obtiene la respuesta en formato JSON.
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        sweetAlert(1, response.message, 'login.html');
                    } else {
                        // Se verifica si el token falló (ya sea por tiempo o por uso).
                        if (response.recaptcha) {
                            sweetAlert(2, response.exception, null);
                        } else {
                            sweetAlert(2, response.exception, null);
                            // Se genera un nuevo token.
                            //reCAPTCHA();
                        }
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        });
    }else{
        sweetAlert(3, 'Revise el mensaje debajo de la contraseña', null);
    }
});