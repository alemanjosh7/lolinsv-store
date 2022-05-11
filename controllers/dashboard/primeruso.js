// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';


//Inicializando metodos y componentes
document.addEventListener('DOMContentLoaded', function () {
    //Inicializando componentes de Materialize
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.Modal.init(document.querySelectorAll('.modal'));
    //Inicializando metodos
    //Comprobar admins
    comprobarAdmins();
});

/*Obtenemos todos los elementos a usar*/
var reiniciarbtn = document.getElementById('reinciar-form');
const CONFIRMARPRIMERADBTN = document.getElementById('confirmar-primerbtn');
const NOMBREADBTN = document.getElementById('nombre-admin');
const APELLIDOADBTN = document.getElementById('apellido-admin');
const USUARIOADBTN = document.getElementById('usuario-admin');
const CONTRASENAADBTN = document.getElementById('contrasena-usu');
const CONTRASENACADBTN = document.getElementById('contrasenac-usu');
const MENSAJE = document.getElementById('indicacion');
const CONFIRMARPRELOADER = document.getElementById('confirmarprimer_preloader');
const CANCELPRIMERBTN = document.getElementById('cancelaprimer_boton');
const CONFIRMARMODAL = document.getElementById('confirmar-primeruso');
const registrarprimerS = document.getElementById('registrarprimer');
const VEROCULTARPASS = document.getElementById('ocultarmostrar_contraseñas');

/*Crear metodo para ocultar mostrar contraseñas*/
VEROCULTARPASS.addEventListener('click', function () {
    if (CONTRASENAADBTN.type == 'password') {
        CONTRASENAADBTN.type = 'text';
        CONTRASENACADBTN.type = 'text';
        VEROCULTARPASS.innerHTML = "visibility_off";
    } else {
        CONTRASENAADBTN.type = 'password';
        CONTRASENACADBTN.type = 'password';
        VEROCULTARPASS.innerHTML = "visibility";
    }
})

/*Crear Metodo que limpie los campos al dar click al boton de reiniciar*/
reiniciarbtn.addEventListener('click', function () {
    //Limpiamos los campos
    //Creamos arreglo de los campos a limpiar
    let arregloLC = [NOMBREADBTN, APELLIDOADBTN, USUARIOADBTN, CONTRASENAADBTN, CONTRASENACADBTN];
    //Ejecutamos la función dentro de la clase de validaciones
    borrarCampos(arregloLC);
    MENSAJE.style.display = 'none';
});

/*Crear Metodo que indique si las contraseñas son iguales*/
function contrasenasIguales() {
    let contra1 = CONTRASENAADBTN.value;
    let contra2 = CONTRASENACADBTN.value;

    if (contra1 != contra2) {
        MENSAJE.innerText = 'Las contraseñas no coinciden';
        MENSAJE.style.display = 'block';
    }else if(contra1.length<6){
        MENSAJE.innerText = 'Las contraseñas deben tener más de 6 caracteres';
        MENSAJE.style.display = 'block';
    }
    else {
        MENSAJE.style.display = 'none';
    }
}
CONTRASENAADBTN.addEventListener('keyup', function () {
    contrasenasIguales();
});
CONTRASENACADBTN.addEventListener('keyup', function () {
    contrasenasIguales();
});

/*Función cuando se cancele el registro*/
CANCELPRIMERBTN.addEventListener('click', function () {
});

/*Función para cuando se quiera crear el registro del primer admin*/
registrarprimer.addEventListener('click', function (event) {
    event.preventDefault();
    //Evaluamos si no hay campos vacios
    //Creamos arreglo de los campos a evaluar
    let arregloVCV = [NOMBREADBTN, APELLIDOADBTN, USUARIOADBTN, CONTRASENAADBTN, CONTRASENACADBTN];
    //Ejecutamos la función dentro de la clase de validaciones
    if (validarCamposVacios(arregloVCV) != false) {
        MENSAJE.style.display = 'none';
        contrasenasIguales();
        if (MENSAJE.style.display != 'block') {
            let modal = M.Modal.getInstance(CONFIRMARMODAL);
            modal.open();
        }
    } else {
        MENSAJE.innerText = 'No se permiten campos vacios'
        MENSAJE.style.display = 'block';
    }
})

/*Función cuando se confirme la creación*/
CONFIRMARPRIMERADBTN.addEventListener('click', function () {
    //Mostramos el preloader
    CONFIRMARPRELOADER.style.display = 'block';
    CONFIRMARPRIMERADBTN.classList.add('disabled');
    CANCELPRIMERBTN.classList.remove('disabled');
    //Se ejecuta la operación para registrar el administrador
    fetch(API_ADMINS + 'register', {
        method: 'post',
        body: new FormData(document.getElementById('registrar-primerus'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un MENSAJE en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un MENSAJE con la excepción.
                if (response.status) {
                    sweetAlert(1, response.message, 'index.html');
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});

//Función para confirmar si hay admins
// Petición para consultar si existen usuarios registrados.
function comprobarAdmins(){
    fetch(API_ADMINS + 'readUsers', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un MENSAJE en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {
                    location.href = 'inicio.html';
                } else if (response.status) {
                    location.href = 'index.html';
                } else {
                    sweetAlert(4, 'Debe crear un administrador para iniciar a usar el sistema, por favor leer la indicación', null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

