/*Estilo de las opciones de los carritos y el navbar mobile*/
var opcionesCarrito = {
    edge: 'right'
}
var navbarmobile = {
    edge: 'left'
}

const API_USUARIOS = SERVER + 'dashboard/admins.php?action=';
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';

document.addEventListener('DOMContentLoaded', function () {
    comprobarAdmins();
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Sidenav.init(document.querySelectorAll('#mobile-demo'), navbarmobile);
    M.Sidenav.init(document.querySelectorAll('#carrito'), opcionesCarrito);
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.Modal.init(document.querySelectorAll('.modal'));

    fetch(API_USUARIOS + 'readProfile', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del usuario que ha iniciado sesión.
                    document.getElementById('nombre').value = response.dataset[0].nombre_admin;
                    document.getElementById('apellido').value = response.dataset[0].apellido_admin;
                    document.getElementById('usuario').value = response.dataset[0].usuario;
                    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
                    M.updateTextFields();
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));

});

var botonActualizar = document.getElementById('aceptaractdatosperfil_boton');
botonActualizar.addEventListener('click', function () {
    // Petición para actualizar los datos personales del usuario.
    // event.preventDefault();
    fetch(API_USUARIOS + 'editProfile', {
        method: 'post',
        body: new FormData(document.getElementById('profile-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se muestra un mensaje de éxito.
                    sweetAlert(1, response.message, 'perfilpriv.html');
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});

var botonRestablecer = document.getElementById('restablecerContraseña');
botonRestablecer.addEventListener('click', () => {
    fetch(API_USUARIOS + 'changePassword', {
        method: 'post',
        body: new FormData(document.getElementById('renovarcontr-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se muestra un mensaje de éxito.
                    sweetAlert(1, response.message, 'perfilpriv.html');
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
})

document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems);
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
/*Mostrar u ocultar para dui y número en el perfil*/
/*DUI*/
var ojo = document.getElementById('ocultarmostrar_duiuser');
var dui = document.getElementById('dui_usuario');

/*Telefono*/
var ojo2 = document.getElementById('ocultarmostrar_teleuser');
var telefono = document.getElementById('telefono_usuario');


/*Mostrar-Ocultar preloader para la actualización de datos del perfil*/
var btnactperfil = document.getElementById('aceptaractdatosperfil_boton');
var preloaderactperfil = document.getElementById('actdatosperfil_preloader');
var btncancelperfil = document.getElementById('cancelactdatosperfil_boton');
btnactperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "block";
    btnactperfil.classList.add("disabled")
});

btncancelperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "none";
    btnactperfil.classList.remove("disabled")
});

/*Mostrar-Ocultar preloader para la actualización de contraseña del perfil*/
var btnactcontra = document.getElementById('aceptaractcontra_boton');
var preloaderactcontra = document.getElementById('actdatoscontra_preloader');
var btncancelcontra = document.getElementById('cancelactdatoscontra_boton');

/* Mostrar/Ocultar contraseñas para la actualización*/
let ojo3 = document.getElementById('ocultarmostrar_contraseñas');
/*Ocultar el NavBar si se aprieta en seguir viendo*/
var btncontinuarv = document.getElementById('seguirv_carrito');
/*Ocultar navbar mobile tras aparecer carrito*/
var btnabrircarrito = document.getElementById('abrircarrito-mobile');

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
                } else if (response.status) {
                    location.href = 'index.html';
                } else {
                    location.href = 'primeruso.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}