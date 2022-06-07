const API_PEDIDOS = SERVER + 'public/pedidosEstablecidos.php?action=';
// const API_GLBVAR = SERVER + 'variablesgb.php?action=';

document.addEventListener('DOMContentLoaded', function () {
    //Iniciamos algunos metodos
    obtenerIdProducto();//Comprobamos y obtenemos el id del producto seleccionado
});

document.getElementById('shopping-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Petición para agregar un producto al pedido.
    fetch(API_PEDIDOS + 'createDetail', {
        method: 'post',
        body: new FormData(document.getElementById('shopping-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
                if (response.status) {
                    sweetAlert(1, response.message, '');
                    obtenerIdProducto();
                } else {
                    // Se verifica si el cliente ha iniciado sesión para mostrar la excepción, de lo contrario se direcciona para que se autentique. 
                    if (response.session) {
                        sweetAlert(2, response.exception, null);
                    } else {
                        sweetAlert(3, response.exception, 'login.html');
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});
