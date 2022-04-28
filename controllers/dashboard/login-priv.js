// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';

//Inicializando las funciones y componentes
document.addEventListener('DOMContentLoaded', function () {
    /*Inicializando componentes de Materialize*/
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Modal.init(document.querySelectorAll('.modal'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    //Metodos de comprobación
    //Comprobar si hay administradores
    comprobarAdmins();

});
//declarando algunos componentes
const LOGINBTN = document.getElementById('iniciarsesion_boton');//Boton de inicio de sesión
const USUARIOTXT = document.getElementById('username');//input del nombre del usuario
const CONTRAINPUT = document.getElementById('contraseña');//input de la contraseña de usuario
const CONTRAN = document.getElementById('contraseña_nueva');//input de la contraseña nueva en restablecer contraseña
const CONTRAC = document.getElementById('contraseña_confirma');//input de la confirmación de la contraseña en restablecer contraseña
const preloader = document.getElementById('actdatoscontra_preloader');//preloader de la actualización de contraseña
const RESTABLECERCTR = document.getElementById('restablecerContraseña');//boton de restablecer contraseña
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
/*Validar el PIN de restablecer contraseñas*/
var comprobarPIN = document.getElementById('recuperarPIN');
comprobarPIN.addEventListener('click', function () {
    let pinintro = document.getElementById('PIN-numeros');
    let mensaje = document.getElementById('mensaje-PIN');
    let modal = M.Modal.getInstance(document.querySelector('#modalPIN'));
    let restablecermodal = M.Modal.getInstance(document.querySelector('#modalrestablecer'));
    if(USUARIOTXT.value.length!=0){
        mensaje.style.display = 'none';
        if (pinintro.value.length != 0) {
            fetch(API_GLBVAR + 'comprobarPINLog', {
                method: 'post',
                body: new FormData(document.getElementById('pin-sesion'))
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                        if (response.session) {
                        } else if (response.status) {
                            modal.close();
                            restablecermodal.open();
                        } else {
                            sweetAlert(2,response.exception,null)
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            });
        } else {
            mensaje.style.display = 'block';
            mensaje.innerText = 'No se permiten espacios vacios';
        }
    }else{
        mensaje.style.display = 'block';
        mensaje.innerText = 'Por favor coloque su usuario en el formulario anterior antes de continuar';
    }
});

//Mostrar ocultar contraseña
var ojo = document.getElementById('ocultarmostrar_contraseña');
ojo.addEventListener('click', function () {
    if (CONTRAINPUT.type == "password") {
        CONTRAINPUT.type = "text"
        ojo.innerText = "visibility_off"
    } else {
        CONTRAINPUT.type = "password"
        ojo.innerText = "visibility"
    }
});

//Mostrar-Ocultar restablecer contraseñas
var ojo2 = document.getElementById('ocultarmostrar_contraseñas');
ojo2.addEventListener('click', function () {
    let CONTRAINPUT = document.getElementById('contraseña_nueva');
    let CONTRAC = document.getElementById('contraseña_confirma');
    if (CONTRAINPUT.type == "password") {
        CONTRAINPUT.type = "text";
        CONTRAC.type = "text";
        ojo2.innerText = "visibility_off";
    } else {
        CONTRAINPUT.type = "password";
        CONTRAC.type = "password"
        ojo2.innerText = "visibility";
    }
});


//Validar contraseñas iguales y campos vacios en el restablecer contraseña modal y actualizar la contraseña
RESTABLECERCTR.addEventListener('click', function () {
    /*Creamos una variable del mensaje dentro del formulario*/
    let mensaje = document.getElementById('mensaje-restablecer');//mensaje
    let restablecermodal = M.Modal.getInstance(document.querySelector('#modalrestablecer'));//modal
    //creamos el form para añadir el usuario
    let form = new FormData(document.getElementById('renovarcontr-form'));
    form.append('usuario',USUARIOTXT.value);
    if (CONTRAN.value.length != 0 || CONTRAC.value.length != 0) {
        contrasenasIguales();
        if (mensaje.style.display != 'block') {
            //Ejecutamos metodo que busque el usuario de acuerdo al escrito
            RESTABLECERCTR.classList.add('disabled');
            preloader.style.display = 'block';
            fetch(API_ADMINS + 'actualizarContraLog', {
                method: 'post',
                body: form
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                        if (response.session) {
                            location.href = 'inicio.html';
                        } else if (response.status) {
                            preloader.style.display = 'none';
                            RESTABLECERCTR.classList.remove('disabled');
                            sweetAlert(1,response.message,null)
                            restablecermodal.close();
                        } else {
                            preloader.style.display = 'none';
                            RESTABLECERCTR.classList.remove('disabled');
                            sweetAlert(2,response.exception,null)
                        }
                    });
                } else {
                    RESTABLECERCTR.classList.remove('disabled');
                    console.log(request.status + ' ' + request.statusText);
                }
            });
        } else {
            mensaje.innerText = 'Las contraseñas deben coincidir';
            mensaje.style.display = 'block';
        }
    } else {
        mensaje.style.display = 'block';
        mensaje.innerText = 'No se permiten espacios vacios';
    }
});

// Petición para consultar si existen usuarios registrados.
function comprobarAdmins() {
    fetch(API_ADMINS + 'readUsers', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {
                    location.href = 'inicio.html';
                } else if (response.status) {
                } else {
                    location.href = 'primeruso.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Función de log in
LOGINBTN.addEventListener('click', function () {
    if (USUARIOTXT.value.length > 0 && CONTRAINPUT.value.length > 0) {
        LOGINBTN.classList.add('disabled');
        fetch(API_ADMINS + 'logIn', {
            method: 'post',
            body: new FormData(document.getElementById('session-form'))
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        sweetAlert(1, response.message, 'inicio.html');
                        LOGINBTN.classList.remove('disabled');
                    } else {
                        LOGINBTN.classList.remove('disabled');
                        sweetAlert(2, response.exception, null);
                    }
                });
            } else {
                LOGINBTN.classList.remove('disabled');
                console.log(request.status + ' ' + request.statusText);
            }
        });
    } else {
        sweetAlert(3, 'Debe de completar el formulario para iniciar sesion', null);
    }
});
//Función para comprobar si las contraseñas son iguales
function contrasenasIguales() {
    let mensaje = document.getElementById('mensaje-restablecer');//mensaje
    if (CONTRAN.value != CONTRAC.value) {
        mensaje.innerText = 'Las contraseñas no coinciden';
        mensaje.style.display = 'block';
    }else if(CONTRAN.value.length<6){
        mensaje.innerText = 'Las contraseñas deben tener más de 6 caracteres';
        mensaje.style.display = 'block';
    }
    else {
        mensaje.style.display = 'none';
    }
}
//Funciónes para comprobar las contraseñas mientras estan siendo escritas
CONTRAN.addEventListener('keyup', function () {
    contrasenasIguales();
});
CONTRAC.addEventListener('keyup', function () {
    contrasenasIguales();
});
