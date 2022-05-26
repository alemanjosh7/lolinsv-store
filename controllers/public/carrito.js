// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CARRITO = SERVER + 'public/carrito.php?action=';
const API_GLBVARC = SERVER + 'variablesgb.php?action=';

var opcionesCarrito = {
    edge: 'right',
    onOpenStart: function () {
        cargarCarrito();
    }
}
var navbarmobile = {
    edge: 'left'
}

//Metodo para comprobar que halla un carrito de compras que cargar
function cargarCarrito() {
    fetch(API_GLBVARC + 'verificarCarrito', {
        method: 'get',
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {
                } else if (response.status) {
                    //Como hay un carrito, ejecutaremos el metodo que cargue los campos dentro del carrito
                    console.log(response.id_pedidoEsta);
                    cargarDetalle(response.id_pedidoEsta);
                } else {
                    console.log('No hay');
                    //Como no hay un carrito, entonces colocaremos un texto informando 
                    let h = document.createElement("h5");
                    let text = document.createTextNode("Aun no ha añadido productos al carrito");
                    h.appendChild(text);
                    document.getElementById('elemcarr-form').innerHTML = '';
                    document.getElementById('elemcarr-form').append(h);
                    document.getElementById('pagot_carrito').innerText = '$0.00';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}


function cargarDetalle(id) {
    //Enviamos la petición a la api pidiendo el detalle del pedido establecido
    //Creamos un formulario 
    let form = new FormData();
    //Le asignamos el id
    //Iniciamos variable de contenido
    let content = '';
    form.append('id', id);
    fetch(API_CARRITO + 'obtenerCarrito', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    let data = response.dataset;
                    data.map(function (row) {
                        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                        content += `
                        <!--Elementos de la compra en el carrito-->
                        <!--Elemento-->
                        <div class="row">
                            <!--Fondo del elemento-->
                            <div class="col s12" id="fondocompra-carrito1">
                                <!--Icono de eliminar-->
                                <div class="elimardel-carrito" onclick="delDet(${row.id_detalle_pedidos},${row.id_pedidos_establecidos})">
                                    <img class="responsive-img"
                                        src="../../resources/img/icons/eliminardel-carrito.png" id="eliminar-prdcarr">
                                </div>
                                <!--Contenedor del contenido del carrito-->
                                <div class="row" id="fila-contenidocarrito">
                                    <!--Columna de la imagen-->
                                    <div class="col s6 center-align" id="imagencontenido-carrito">
                                        <img class="responsive-img"
                                            src="../../api/images/productos/${row.imagen_producto}" height="276px"
                                            width="254">
                                    </div>
                                    <!--Columna del detalle del contenido-->
                                    <div class="col s6">
                                        <!--Fila de los detalles-->
                                        <div class="row">
                                            <!--Columna del nombre del producto-->
                                            <div class="col s12 center-align">
                                                <h6 id="nombreAmigurumi_carrito">${row.nombre_producto}</h6>
                                            </div>
                                            <!--Columna de la cantidad-->
                                            <div class="col s12" id="coldetalle-carrito">
                                                <div class="col l1 s1 left-align">
                                                    <p><b>Cantidad:</b></p>
                                                </div>
                                                <div class="col l1 offset-l6 s1 offset-s6 right-align">
                                                    <p id="cantidaddetalle_carrito">${row.cantidad_detallep}</p>
                                                </div>
                                            </div>
                                            <!--Columna del precio-->
                                            <div class="col s12" id="coldetalle-carrito">
                                                <div class="col l1 s1 left-align">
                                                    <p><b>Precio:</b></p>
                                                </div>
                                                <div class="col l1 offset-l3 s1 offset-s5 right-align">
                                                    <p id="preciodetalle_carrito">$${row.precio_producto}</p>
                                                </div>
                                            </div>
                                            <!--Columna del subtotal-->
                                            <div class="col s12" id="coldetalle-carrito">
                                                <div class="col l1 s1 left-align">
                                                    <p><b>Subtotal:</b></p>
                                                </div>
                                                <div class="col l1 offset-l4 s1 offset-s5 right-align">
                                                    <p id="preciodetalle_carrito">&nbsp$${row.subtotal_detallep}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                    //Colocamos los detalles en el contenedor de los detalles
                    document.getElementById('elemcarr-form').innerHTML = content;
                    //Colocamos el total a pagar en el componente
                    document.getElementById('pagot_carrito').innerText = '$'+response.dataset[0].montototal_pedidoesta;
                } else {
                    document.getElementById('elemcarr-form').innerHTML = '';
                    sweetAlert(2, 'No se ha logrado cargar el detalle del carrito', null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
            NOMBREPROD.value = '';
        }
    });
}

//Función de eliminar un detalle del pedido
function delDet(id,idp){
    const data = new FormData();
    data.append('id', id);
    data.append('idp', idp);
    //Cargamos la advertencia
    Swal.fire({
        title: 'Advertencia',
        text: '¿Desea eliminar el producto del carrito?',
        icon: 'warning',
        showDenyButton: true,
        confirmButtonText: 'Si',
        denyButtonText: 'Cancelar',
        allowEscapeKey: false,
        allowOutsideClick: false,
        background: '#F7F0E9',
        confirmButtonColor: 'green',
    }).then(function (value) {
        // Se comprueba si fue cliqueado el botón Sí para hacer la petición de borrado, de lo contrario no se hace nada.
        if (value.isConfirmed) {
            fetch(API_CARRITO + 'delete', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    // Se obtiene la respuesta en formato JSON.
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Se cargan nuevamente el carrito
                            cargarDetalle(idp);
                            sweetAlert(1, response.message, null);
                        } else {
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            });
        }
    });
}