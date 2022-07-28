// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
//Iniciando las funciones y componentes
// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    saludo();
    comprobarAdmins();
});
//Declaramos algunos componentes
const saludoUsuario = document.getElementById('saludo-usuario');

//Creando función para el saludo
function saludo() {
    // Se define un objeto con la fecha y hora actual.
    let today = new Date();
    // Se define una variable con el número de horas transcurridas en el día.
    let hour = today.getHours();
    // Se define una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (hour < 12) {
        greeting = 'mañana';
    } else if (hour < 19) {
        greeting = 'tarde';
    } else if (hour <= 23) {
        greeting = 'noche';
    }
    fetch(API_GLBVAR + 'verificarSaludoI', {
        method: 'get',
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {
                } else if (response.status) {
                    sweetAlert(4, "Bienvenido " + response.nombre + " " + response.apellido + " ¡Ten una " + greeting + " productiva!", null);
                    saludoUsuario.textContent = 'Felíz ' + greeting + " " + response.nombre + " " + response.apellido;
                } else {
                    saludoUsuario.textContent = 'Felíz ' + greeting + " " + response.nombre + " " + response.apellido;
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Función para confirmar si hay admins
// Petición para consultar si existen usuarios registrados.
function comprobarAdmins(){
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
                    sweetAlert(4, 'Debe crear un administrador para iniciar a usar el sistema, por favor leer la indicación', null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}