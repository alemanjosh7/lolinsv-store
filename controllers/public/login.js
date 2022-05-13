// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES = SERVER + 'public/login.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';

/*Estilo de las opciones de los carritos y el navbar mobile*/

//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    comprobaClientes();

    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Sidenav.init(document.querySelectorAll('#mobile-demo'), navbarmobile);
    M.Sidenav.init(document.querySelectorAll('#carrito'), opcionesCarrito);
    M.Modal.init(document.querySelectorAll('.modal'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}

//declarando algunos componentes
const LOGINBTN = document.getElementById('iniciarsesion_boton');//Boton de inicio de sesión
const USUARIOTXT = document.getElementById('username');//input del nombre del usuario
const CONTRAINPUT = document.getElementById('contraseña');//input de la contraseña de usuario
/*Copiar número de Whatsaap en el Footer*/


/*Validar el PIN del correo MOMENTANEO PARA PRACTICIDAD*/
var comprobarPIN = document.getElementById('recuperarPIN');
comprobarPIN.addEventListener('click', function () {
    let pin = 123;
    let pinintro = document.getElementById('PIN-numeros');
    let mensaje = document.getElementById('mensaje-PIN');
    let modal = M.Modal.getInstance(document.querySelector('#modalPIN'));
    let restablecermodal = M.Modal.getInstance(document.querySelector('#modalrestablecer'));
    if (pinintro.value.length != 0) {
        if (pin == pinintro.value) {
            modal.close();
            mensaje.style.display = 'none';
            pinintro.value = '';
            restablecermodal.open();
        } else {
            mensaje.innerText = 'El pin no coincide';
            mensaje.style.display = 'block';
        }
    } else {
        mensaje.style.display = 'block';
        mensaje.innerText = 'No se permiten espacios vacios';
    }
});
//Validar solo números
function solonumeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros y letras
    patron = /[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

//Mostrar ocultar contraseña
var ojo = document.getElementById('ocultarmostrar_contraseña');
ojo.addEventListener('click', function () {
    let contrainput = document.getElementById('contraseña');
    if (contrainput.type == "password") {
        contrainput.type = "text"
        ojo.innerText = "visibility_off"
    } else {
        contrainput.type = "password"
        ojo.innerText = "visibility"
    }
});

//Mostrar-Ocultar restablecer contraseñas
var ojo2 = document.getElementById('ocultarmostrar_contraseñas');
ojo2.addEventListener('click', function () {
    let contrainput = document.getElementById('contraseña_nueva');
    let contrac = document.getElementById('contraseña_confirma');
    if (contrainput.type == "password") {
        contrainput.type = "text";
        contrac.type = "text";
        ojo2.innerText = "visibility_off";
    } else {
        contrainput.type = "password";
        contrac.type = "password"
        ojo2.innerText = "visibility";
    }
});

//contador para reenviar PIN de 30s
var reenviarPIN = document.getElementById('reenviarPIN');
var contador = document.getElementById('cronometro');
let seg = 0;
var cronometro;
reenviarPIN.addEventListener('click', function () {
    cronometro = setInterval(function () {
        if (seg >= 0) {
            if (seg == 31) {
                reenviarPIN.style.opacity = '1';
                contador.style.display = "none";
                clearInterval(cronometro);
                seg=0;
            } else {
                contador.style.display = "block";
                reenviarPIN.style.opacity = "0.8";
                contador.innerHTML = seg + "s";
                seg++;
            }
        }else{
            
        }
    }, 1000);
});

//Ocultar el contador si se cancela
var btncancelr = document.getElementById('cancelar_restablecer');
var btncancelrc = document.getElementById('cancelarPIN');
var preloader = document.getElementById('actdatoscontra_preloader-login');
btncancelr.addEventListener('click', function () {
    reenviarPIN.style.opacity = 1;
    contador.style.display = "none";
    preloader.style.display = "block";
    clearInterval(cronometro);
    seg = 0;
});

btncancelrc.addEventListener('click', function(){
    preloader.style.display = "none";
    restablecerctr.classList.remove("disabled");
    let contran = document.getElementById('contraseña_nueva');
    let contrac = document.getElementById('contraseña_confirma');
    contrac.value = '';
    contran.value = '';
});

//Validar contraseñas iguales y campos vacios en el restablecer contraseña
var restablecerctr = document.getElementById('restablecerContraseña');

restablecerctr.addEventListener('click',function(){
    let contran = document.getElementById('contraseña_nueva');
    let contrac = document.getElementById('contraseña_confirma');
    let mensaje = document.getElementById('mensaje-restablecer');
    if (contran.value.length != 0 || contrac.value.length != 0) {
        if (contran.value == contrac.value) {
            restablecerctr.classList.add("disabled");
            preloader.style.display = "block";
            mensaje.style.display = 'none';
            contra.value = '';
            contrac.value = '';
            contran.value = '';
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
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}


//Función de log in
LOGINBTN.addEventListener('click',function(){
    if (USUARIOTXT.value.length > 0 && CONTRAINPUT.value.length > 0) {
        LOGINBTN.classList.add('disabled');
        fetch(API_CLIENTES + 'logIn', {
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

