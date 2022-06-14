// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES = SERVER + 'public/login.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';

/*Estilo de las opciones de los modals*/
var pinmodal = {
    onOpenStart: function () {
        // Se restauran los elementos del formulario.
        generarPIN();
        let mensaje = document.getElementById('mensaje-PIN');
        mensaje.style.display = 'none';
    }
}

var restablecerctrmodal = {
    onOpenStart: function () {
        let mensaje = document.getElementById('mensaje-restablecer');
        preloader.style.display = "none";
        restablecerctr.classList.remove("disabled");
        let contran = document.getElementById('contraseña_nueva');
        let contrac = document.getElementById('contraseña_confirma');
        contrac.value = '';
        contran.value = '';
        mensaje.style.display = 'none';
    }
}

//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    comprobaClientes();
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Modal.init(document.querySelectorAll('.modal'));
    M.Modal.init(document.querySelectorAll('#modalPIN'), pinmodal);
    M.Modal.init(document.querySelectorAll('#modalrestablecer'), restablecerctrmodal);
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
const CONTRAN = document.getElementById('contraseña_nueva');//input de la contraseña nueva en restablecer contraseña
const CONTRAC = document.getElementById('contraseña_confirma');//input de la confirmación de la contraseña en restablecer contraseña
/*Copiar número de Whatsaap en el Footer*/


/*Validar el PIN del correo MOMENTANEO PARA PRACTICIDAD*/
var comprobarPIN = document.getElementById('recuperarPIN');
comprobarPIN.addEventListener('click', function () {
    //Validamos que halla colocado un usuario en el formulario anterior
    let pinintro = document.getElementById('PIN-numeros');
    let mensaje = document.getElementById('mensaje-PIN');
    let modal = M.Modal.getInstance(document.querySelector('#modalPIN'));
    let restablecermodal = M.Modal.getInstance(document.querySelector('#modalrestablecer'));
    let form = new FormData();
    form.append('pin', pinintro.value);
    if (USUARIOTXT.value.length != 0) {
        if (pinintro.value.length != 0) {
            fetch(API_GLBVAR + 'comprobarPINRCR', {
                method: 'post',
                body: form
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status && comprobarHora(response.hora)) {
                            modal.close();
                            mensaje.style.display = 'none';
                            pinintro.value = '';
                            restablecermodal.open();
                        } else {
                            mensaje.innerText = 'El pin no coincide o ha caducado, por favor reenviar uno nuevo ';
                            mensaje.style.display = 'block';
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
    } else {
        mensaje.innerText = 'Coloque el usuario en el formulario anterior';
        mensaje.style.display = 'block';
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
    if (seg == 0) {
        cronometro = setInterval(function () {
            if (seg >= 0) {
                if (seg == 31) {
                    reenviarPIN.style.opacity = '1';
                    contador.style.display = "none";
                    clearInterval(cronometro);
                    seg = 0;
                } else {
                    contador.style.display = "block";
                    reenviarPIN.style.opacity = "0.8";
                    contador.innerHTML = seg + "s";
                    seg++;
                }
            } else {

            }
        }, 1000);
        //Ejecutamos el metodo de generar pin para reenviarlo
        generarPIN();
    }
});

//Ocultar el contador si se cancela
var btncancelr = document.getElementById('cancelar_restablecer');
var btncancelrc = document.getElementById('cancelarPIN');
var preloader = document.getElementById('actdatoscontra_preloader-login');
btncancelr.addEventListener('click', function () {
    reenviarPIN.style.opacity = 1;
    contador.style.display = "none";
    clearInterval(cronometro);
    seg = 0;
});

btncancelrc.addEventListener('click', function () {
    preloader.style.display = "none";
    restablecerctr.classList.remove("disabled");
    let contran = document.getElementById('contraseña_nueva');
    let contrac = document.getElementById('contraseña_confirma');
    contrac.value = '';
    contran.value = '';
});

//Validar contraseñas iguales y campos vacios en el restablecer contraseña
var restablecerctr = document.getElementById('restablecerContraseña');

restablecerctr.addEventListener('click', function () {
    //Creamos las variables de los componentes y otras variables utiles
    let contran = document.getElementById('contraseña_nueva');
    let contrac = document.getElementById('contraseña_confirma');
    let mensaje = document.getElementById('mensaje-restablecer');
    let restablecermodal = M.Modal.getInstance(document.querySelector('#modalrestablecer'));
    //Creamos el formulario al cual le añadiremos los elementos
    let form = new FormData();
    form.append('usuario', USUARIOTXT.value);//Usuario
    form.append('contrasena', contran.value);//Contraseña
    if (contran.value.length != 0 || contrac.value.length != 0) {
        contrasenasIguales();
        if (contran.value == contrac.value && mensaje.style.display != 'block') {
            //Mostramos el preloader y desactivamos los botones
            restablecerctr.classList.add("disabled");
            btncancelrc.classList.add("disabled");
            preloader.style.display = "block";
            //Ejecutamos el metodo para actualizar la contraseña
            fetch(API_CLIENTES + 'actualizarContraLog', {
                method: 'post',
                body: form
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            sweetAlert(1, response.message, null);
                            restablecerctr.classList.remove("disabled");
                            btncancelrc.classList.remove("disabled");
                            preloader.style.display = "none";
                            mensaje.style.display = 'none';
                            contrac.value = '';
                            contran.value = '';
                            restablecermodal.close();
                        } else {
                            restablecerctr.classList.remove("none");
                            preloader.style.display = "none";
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    restablecerctr.classList.add("disabled");
                    preloader.style.display = "none";
                    console.log(request.status + ' ' + request.statusText);
                }
            });
        } else {
            mensaje.innerText = 'Las contraseñas deben coincidir o ser mayores que 6 caracteres';
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
                else {
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
        fetch(API_CLIENTES + 'logIn', {
            method: 'post',
            body: new FormData(document.getElementById('session-form'))
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        sweetAlert(1, response.message, 'index.html');
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

function generarPIN() {
    //Generamos la hora en que se creo el pin
    let hora = new Date().getHours();
    console.log(hora);
    //Añadimos ese pin a la variable de sessión de PIN
    //Creamos un formulario y añadimos el pin al formulario
    let form = new FormData();
    form.append('hora', hora);
    fetch(API_GLBVAR + 'setPINCTRR', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    //console.log('El pin se ha seteado' + ' ' + response.PIN);
                    //Enviamos el mensaje
                    enviarPINCorreo();
                } else {
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

function enviarPINCorreo() {
    //Primero obtenemos el correo del usuario
    //Creamos un formulario y añadimos el nombre del usuario y realizamos la petición
    let url = SERVER + 'enviarCorreo.php';
    if (USUARIOTXT.value.length > 0) {
        form = new FormData();
        form.append('usuario', USUARIOTXT.value);
        let correo;
        fetch(API_CLIENTES + 'obtenerCorreo', {
            method: 'post',
            body: form
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        correo = response.dataset.correo_cliente;
                        console.log(correo);
                        form.append('correo', correo);
                        form.append('nombre', response.dataset.nombre_cliente);
                        form.append('apellido', response.dataset.apellido_cliente);
                        //Una vez seteado ejecutamos el metodo para enviar el correo
                        fetch(url, {
                            method: 'post',
                            body: form
                        }).then(function (request) {
                            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                            if (request.ok) {
                                request.json().then(function (response) {
                                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                                    if (response.status) {
                                        console.log('Mensaje enviado con exito');
                                    } else {
                                        sweetAlert(2, response.exception, null);
                                    }
                                });
                            } else {
                                LOGINBTN.classList.remove('disabled');
                                console.log(request.status + ' ' + request.statusText);
                            }
                        });
                    } else {
                        sweetAlert(2, response.exception, null);
                    }
                });
            } else {
                LOGINBTN.classList.remove('disabled');
                console.log(request.status + ' ' + request.statusText);
            }
        });
    } else {
        sweetAlert(3, 'Favor llenar el campo de usuario en el formulario anterior', null);
    }

}

//Función para comprobar que halla pasado una hora
function comprobarHora(hora) {
    let horaact = new Date().getHours();
    console.log(horaact)
    if (horaact == hora) {
        return true;
    } else {
        console.log('no son igual');
        return false;
    }
}

//Función para comprobar si las contraseñas son iguales
function contrasenasIguales() {
    let mensaje = document.getElementById('mensaje-restablecer');//mensaje
    if (CONTRAN.value != CONTRAC.value) {
        mensaje.innerText = 'Las contraseñas no coinciden';
        mensaje.style.display = 'block';
    } else if (CONTRAN.value.length < 6) {
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