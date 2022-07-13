// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
const API_PRODUCTOS = SERVER + 'dashboard/productos.php?action=';
const API_CLIENTES = SERVER + 'dashboard/clientes.php?action=';
const API_INVENTARIO = SERVER + 'dashboard/inventario.php?action=';
//Iniciando las funciones y componentes
// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    saludo();
    comprobarAdmins();
    graficoDonaCompras();
    graficoPieAdmins();
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
                    sweetAlert(4, 'Debe crear un administrador para iniciar a usar el sistema, por favor leer la indicación', null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

function graficoPieAdmins() {
    // Petición para obtener los datos del gráfico.
    fetch(API_INVENTARIO + 'adminsConMasRegistros', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a gráficar.
                    let admins = [];
                    let porcentajes = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        admins.push(row.nombre_admin);
                        porcentajes.push(row.cuenta);
                    });
                    // Se llama a la función que genera y muestra un gráfico de pastel. Se encuentra en el archivo components.js
                    pieGraph('chart1', admins, porcentajes, 'Admins con más registros');
                } else {
                    document.getElementById('chart1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

function graficoDonaCompras() {
    // Petición para obtener los datos del gráfico.
    fetch(API_CLIENTES + 'clientesConMasCompras', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a gráficar.
                    let clientes = [];
                    let porcentajes = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        clientes.push(row.nombre_cliente);
                        porcentajes.push(row.suma);
                    });
                    // Se llama a la función que genera y muestra un gráfico de pastel. Se encuentra en el archivo components.js
                    doughnutGraph('chart2', clientes, porcentajes, 'Clientes con mayor porcentaje de compra');
                } else {
                    document.getElementById('chart2').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}