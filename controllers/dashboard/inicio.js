// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
const API_PRODUCTOS = SERVER + 'dashboard/productos.php?action=';
const API_CLIENTES = SERVER + 'dashboard/clientes.php?action=';
const API_INVENTARIO = SERVER + 'dashboard/inventario.php?action=';
const API_CATEGORIA = SERVER + 'dashboard/categorias.php?action=';
//Iniciando las funciones y componentes
// Método manejador de eventos que se ejecuta cuando el documento ha cargado.

document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    saludo();
    comprobarAdmins();
    //Ejecutando graficas
    graficoLineasProductosCM();//Productos con menores cantidades
    graficoDonaCompras();//Clientes con las compras más grandes
    graficoPieAdmins();//últimos admins que han registrado existencias en inventario
    graficoBarrasCategorias();//Número de productos por categorías
    graficoPolarProductoC();//gráfico polar de la cantidad de productos venidos
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

//Metodo para la grafica de lineas sobre los productos con menor cantidad
function graficoLineasProductosCM() {
    // Petición para obtener los datos del gráfico.
    fetch(API_PRODUCTOS + 'productosCantidadM', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let producto = [];
                    let cantidades = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        producto.push(row.nombre_producto);
                        cantidades.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    lineGraph('grf_linea1', producto, cantidades, 'Cantidad de productos', '');
                } else {
                    document.getElementById('grf_linea1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Función de la grafica de dona para las compras más altas de los clientes
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
                    doughnutGraph('grf_dona1', clientes, porcentajes, '','Número de compras');
                } else {
                    document.getElementById('grf_dona1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Función para obtener los últimos administradores que han registrado en el inventario
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
                    pieGraph('grf_pie1', admins, porcentajes, '');
                } else {
                    document.getElementById('grf_pie1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}
//Gráfica de barras de categorias
function graficoBarrasCategorias() {
    // Petición para obtener los datos del gráfico.
    fetch(API_PRODUCTOS + 'productosCantidadCat', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let categorias = [];
                    let cantidades = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        categorias.push(row.nombre_categoria);
                        cantidades.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    barGraph('grf_bar1', categorias, cantidades, 'Cantidad de productos', '');
                } else {
                    document.getElementById('grf_bar1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}
//Gráfica polar de área de la cantidad de productos vendidos Top 5
function graficoPolarProductoC() {
  // Petición para obtener los datos del gráfico.
  fetch(API_CATEGORIA+ 'categoriasConMasItems', {
    method: 'get'
  }).then(function (request) {
    // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
    if (request.ok) {
      request.json().then(function (response) {
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (response.status) {
          // Se declaran los arreglos para guardar los datos a graficar.
          let producto = []
          let cantidades = []
          // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
            response.dataset.map(function (row) {
                // Se agregan los datos a los arreglos.
                producto.push(row.nombre_producto);
                cantidades.push(row.total);
            });
          // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
            // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
            polarAreaGraph('grf_polar1', producto, cantidades, 'Cantidad de productos', '');
        } else {
          document.getElementById('grf_polar1').remove()
          console.log(response.exception)
        }
      })
    } else {
      console.log(request.status + ' ' + request.statusText)
    }
  })
}