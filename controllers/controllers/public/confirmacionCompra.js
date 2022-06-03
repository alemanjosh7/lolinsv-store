// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_PEDIDOEST = SERVER + 'public/confirmacionCmp.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';

//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    //Inicializando componentes de materialize
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    M.Modal.init(document.querySelectorAll('.modal'));
    //Ejecutando algunos metodos para preparar la página
    comprobarPedido();//Cargamos la información del envio
    cargarOrden();//Cargamos los datos de la orden
});
//Declarando algunos componentes
const PRELOADER = document.getElementById('preloader-cl');//Preloader de carga
const CORREOINP = document.getElementById('correo');//Correo del cliente
const NOMBRECL = document.getElementById('nombre_usuario-confirmacion-compra');//Nombre del cliente
const APELLIDOCL = document.getElementById('apellido_usuario-confirmacion-compra');//Nombre del cliente
const DIRECCIONCL = document.getElementById('direccion_usuario-confirmacion-compra');//Nombre del cliente
const DESCRLGE = document.getElementById('preferencia_usuario-confirmacion-compra');//Nombre del cliente
const CONTORD = document.getElementById('contenedor-compras');//Contenedor de la orden
const SUBTINP = document.getElementById('sub_total');//Subtotal de la orden
const TOTALORD = document.getElementById('total');//Total de la orden
const TOTALINP = document.getElementById('total_pagar');//Total a pagar de la orden
const ENCABEZADOMODAL = document.getElementById('encabezado-modal');//Encabezado del modal
const INDICACIONMODAL = document.getElementById('indicacion-modal');//Indicacion del modal

var id_pedidoEsta = null;
var eliminar = false;
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
/*Programación del select de paises*/
const select = document.querySelector('#select');
const opcionesselect = document.querySelector('#opciones-select');
const contenidoselect = document.querySelector('#select .contenido-select');

document.querySelectorAll('#opciones-select > .opcion-select').forEach((opcion) => {
    opcion.addEventListener('click', (e) => {
        e.preventDefault();
        contenidoselect.innerHTML = e.currentTarget.innerHTML;
        opcionesselect.classList.toggle('active');
        select.classList.toggle('active');
    });
});

select.addEventListener('click', function () {
    select.classList.toggle('active');
    opcionesselect.classList.toggle('active');
});

//Metodo para cambiar el estado del pedido_estado a pendiente
/*Mostrar-Ocultar preloader para la confirmación de la compra*/
var btnactperfil = document.getElementById('aceptarcompra_boton');
var preloaderactperfil = document.getElementById('confirmarcompra_preloader');
var btncancelperfil = document.getElementById('cancelacompra_boton');
btnactperfil.addEventListener("click", function () {
    //Analizamos si el metodo es para eliminar o actualizar
    if (eliminar == false) {
        //Analizamos si ya ha añadido una descripción del lugar de entrega
        if (DESCRLGE.value.length > 0) {
            //Mostramos el preloader y desactivamos el boton
            preloaderactperfil.style.display = "block";
            btnactperfil.classList.add("disabled");
            //Creamos un formulario
            let data = new FormData();
            data.append('total', TOTALINP.value);
            data.append('descl', DESCRLGE.value);
            console.log(DESCRLGE.value);
            // Petición para obtener confirmar el pedido
            fetch(API_PEDIDOEST + 'update', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    // Se obtiene la respuesta en formato JSON.
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            PRELOADER.style.display = 'none';
                            btnactperfil.classList.remove("disabled");
                            enviarConfirmacionCompra(id_pedidoEsta);
                            sweetAlert(1, response.message + '. El número de pedido es: ' + id_pedidoEsta + ' por favor guardelo', 'index.html');
                        } else {
                            sweetAlert(2, response.exception, null);
                            PRELOADER.style.display = 'none';
                            btnactperfil.classList.remove("disabled")
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            });
        } else {
            sweetAlert(4, 'Debe de colocar una descripción del lugar de entrega', null);
        }
    } else {
        //Eliminamos el pedido
        // Petición para obtener confirmar el pedido
        fetch(API_PEDIDOEST + 'delete', {
            method: 'get'
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                // Se obtiene la respuesta en formato JSON.
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        PRELOADER.style.display = 'none';
                        btnactperfil.classList.remove("disabled");
                        sweetAlert(1, response.message, 'index.html');
                    } else {
                        sweetAlert(2, response.exception, null);
                        PRELOADER.style.display = 'none';
                        btnactperfil.classList.remove("disabled");
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        });
    }

});

btncancelperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "none";
    btnactperfil.classList.remove("disabled")
});

//Metodo para comprobar si hay un pedido establecido
function comprobarPedido() {
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
                    cargarInfoEnv();
                    id_pedidoEsta = response.id_pedidoEsta;
                } else {
                    //Como no hay un carrito, entonces redireccionaremos al inicio
                    location.href = 'index.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Metodo para cargar los campos dentro del carrito
function cargarInfoEnv() {
    //Se muestra el cargador
    PRELOADER.style.display = 'block';
    // Petición para obtener los datos del registro solicitado.
    fetch(API_PEDIDOEST + 'obtenerPedido', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    //Se llenan los campos
                    CORREOINP.value = response.dataset.correo_cliente;//Correo del cliente
                    NOMBRECL.value = response.dataset.nombre_cliente;//Nombre del cliente
                    APELLIDOCL.value = response.dataset.apellido_cliente;//Apellido del cliente
                    DIRECCIONCL.value = response.dataset.direccion_cliente;//Correo del cliente
                    //Se actualiza los inputs y textarea (Este es especifico) de materialize
                    M.updateTextFields();
                    M.textareaAutoResize(DIRECCIONCL);
                    //Se oculta el cargador
                    PRELOADER.style.display = 'none';
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Creamos función para cargar los datos de la orden
function cargarOrden() {
    //Se muestra el cargador
    PRELOADER.style.display = 'block';
    //Iniciamos variable de contenido
    let content = '';
    // Petición para obtener los datos del registro solicitado.
    fetch(API_PEDIDOEST + 'obtenerOrden', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    //Se llenan los campos
                    let data = response.dataset;
                    data.map(function (row) {
                        content += `
                            <div class="row compra">
                                <div class="col l4 m4 s4 center-align ">
                                    <p>${row.nombre_producto}</p>
                                </div>
                                <div class="col l4 m4 s4 center-align">
                                    <p>${row.cantidad_detallep}</p>
                                </div>
                                <div class="col l4 m4 s4 center-align">
                                    <p>$${row.precio_producto}</p>
                                </div>
                            </div>
                        `;
                    });
                    //Ocultamos el preloader
                    PRELOADER.style.display = 'none';
                    //Colocamos los datos en el contenedor de la orden
                    CONTORD.innerHTML = content;
                    //Colocamos el subtotal y calculamos el total
                    SUBTINP.innerText = '$' + response.dataset[0].montototal_pedidoesta;
                    let total = (Number(response.dataset[0].montototal_pedidoesta) + 5).toFixed(2);
                    TOTALORD.innerText = '$' + total;
                    TOTALINP.value = total;
                } else {
                    sweetAlert(2, response.exception, null);
                    CONTORD.innerHTML = '';
                }
            });
            PRELOADER.style.display = 'none';
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}
//Creamos metodo para preparar el modal para el pago
function prepararPago() {
    //Cambiamos la variable de eliminar a false
    eliminar = false;
    //Cambiamos los textos dentro del header y la indicación
    ENCABEZADOMODAL.innerText = 'Confirmar compra';
    INDICACIONMODAL.innerText = '¿Desea proseguir con la compra?';
}

//Creamos metodo para preparar el modal para eliminar el pedido
function prepararEliminar() {
    //Cambiamos la variable de eliminar a false
    eliminar = true;
    //Cambiamos los textos dentro del header y la indicación
    ENCABEZADOMODAL.innerText = 'Eliminar el pedido';
    INDICACIONMODAL.innerText = '¿Desea limpiar el carrito y eliminar el pedido?';
}

function enviarConfirmacionCompra(id) {
    //Primero obtenemos el correo del usuario
    //Creamos un formulario y añadimos el nombre del usuario y realizamos la petición
    let url = SERVER + 'enviarcorreoconfirmacion.php';
    form = new FormData();
    form.append('id', id);
    let correo;
    fetch(API_PEDIDOEST + 'obtenerPedidoVendido', {
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
                    form.append('total', response.dataset.montototal_pedidoesta);
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
}
/*Opciones xtra
.opciones-select{
    margin-top: 15px;
    border-radius: 5px;
    background-color: white;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, .16);
    max-height: 200px;
    overflow: auto;
    z-index: 100;
    width: 50%;
    display: none;
    position: absolute;
    left: calc(25%);
}
.opciones-select.active{
    display: block;
}
.contenido-opcion{
    width: 100%;
    display: flex;
    align-items: center;
    border-bottom: 2px solid black;
    transition: 2s ease all;
    padding: 5px;
}
.contenido-opcion img{
    position: absolute;
    left: 10%;
}
.contenido-opcion p{
    margin-left: 65px;
}

*/


/*OPCIONES EXTRA*/
/*
.opciones-select{
    margin-top: 15px;
    border-radius: 5px;
    background-color: white;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, .16);
    max-height: 200px;
    overflow: auto;
    z-index: 100;
    width: 100%;
    display: none;
}
/*Contenedor de las opciones cuando esten activas
.opciones-select.active{
    display: block;
}
/*Contenedor de las opciones
.contenido-opcion{
    width: 100%;
    display: flex;
    align-items: center;
    border-bottom: 2px solid black;
    transition: .2s ease all;
    padding: 5px;
}
*/