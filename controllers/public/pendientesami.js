// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
const API_PEDIDOE = SERVER + 'public/pendientesami.php?action=';
const API_CLIENTESENDPOINT = SERVER + 'public/login.php?action=';

var pedido_est = {dismissible: false,

    onCloseEnd: function () {
        // Se restauran los elementos del formulario.
        document.getElementById('form-pedidos').reset();
        TABLEPEDIDO.innerHTML = "";//Vacienado la tabla en caso esté llena
    }}

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    comprobaClientes();//Comprobamos si hay admins
    predecirAdelante();//
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRowsLimit(API_PEDIDOE,0);
    // Se inicializa el componente Modal para que funcionen las cajas de diálogo.
    M.Modal.init(document.querySelectorAll('.modal'), pedido_est);
    //Ocultamos el boton de atras para la páginación
    BOTONATRAS.style.display = 'none';
});

//Declarando algunos componentes
const BUSCADOR_AMI = document.getElementById('search');//Input buscador

const HASTATOP = document.getElementById('hasta_arriba');//Boton de hasta arriba

const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras

const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai

const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf

const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante

const MODALACEPTAR = document.getElementById("modalrestablecer");

const INPUTID = document.getElementById("id_pedido");

const INPUTNOMBRE = document.getElementById("input-nombre");

const INPUTAPELLIDO = document.getElementById("input-apellido");

const INPUTNOMTO = document.getElementById("input-monto");

const INPUTFECHA = document.getElementById("input-fecha");

const INPUTDESCRIP = document.getElementById("input-descripcion");

const BOTONCONFIRMAR = document.getElementById("restablecerContraseña");

const TABLEPEDIDO = document.getElementById("contenido-table");

//Funciones para la páginación
//Función para saber si hay otra página
function predecirAdelante() {
    //Colocamos el boton con un display block para futuras operaciones
    BOTONADELANTE.style.display = 'block';
    //Obtenemos el número de página que seguiría al actual
    let paginaFinal = (Number(BOTONNUMEROPAGF.innerHTML)) + 2;
    console.log("pagina maxima " + paginaFinal);
    //Calculamos el limite que tendria el filtro de la consulta dependiendo de la cantidad de Clientes a mostrar
    let limit = (paginaFinal * 8) - 8;
    console.log("El limite sería: " + limit);
    //Ejecutamos el metodo de la API para saber si hay productos y esta ejecutará una función que oculte o muestre el boton de adelante
    predictLImit(API_PEDIDOE, limit);
}

function ocultarMostrarAdl(result) {
    if (result != true) {
        console.log('Se oculta el boton');
        BOTONADELANTE.style.display = 'none';
    } else {
        //Colocamos el boton con un display block para futuras operaciones
        console.log('Se muestra el boton');
        BOTONADELANTE.style.display = 'block';
    }
}

//Boton de atras
BOTONATRAS.addEventListener('click', function () {
    //Volvemos a mostrár el boton de página adelante
    BOTONADELANTE.style.display = 'block';
    //Obtenemos el número de la página inicial
    let paginaActual = Number(BOTONNUMEROPAGI.textContent);
    //Comprobamos que el número de página no sea igual a 1
    if (paginaActual != 1) {
        //Restamos la cantidad de páginas que queramos que se retroceda en este caso decidi 2 para el botoni y 1 para el botonf
        BOTONNUMEROPAGI.innerHTML = Number(BOTONNUMEROPAGI.innerHTML) - 2;
        BOTONNUMEROPAGF.innerHTML = Number(BOTONNUMEROPAGI.innerHTML) + 1;
        //Verificamos si el número del boton ahora es 1, en caso lo sea se ocultará el boton
        if ((Number(BOTONNUMEROPAGI.innerHTML) - 1) == 0) {
            BOTONATRAS.style.display = 'none';
        }
    }
});

//Boton de adelante
const BUSCADOR = document.getElementById('search');//buscar
BOTONADELANTE.addEventListener('click', function () {
    //Volvemos a mostrár el boton de página anterior
    BOTONATRAS.style.display = 'block';
    //Ejecutamos la función para predecir si hay más páginas
    predecirAdelante();
    //Luego verificamos si el boton de adelante aun continua mostrandose
    if (BOTONADELANTE.style.display = 'block') {
        //Sumamos la cantidad de página que queramos que avance, en este caso decidi 2 para el botoni y 3 para el botonf
        BOTONNUMEROPAGI.innerHTML = Number(BOTONNUMEROPAGI.innerHTML) + 2;
        BOTONNUMEROPAGF.innerHTML = Number(BOTONNUMEROPAGI.innerHTML) + 1;
    }
});

//Función que realizará los botones con numero de la páginacion
document.querySelectorAll(".contnpag").forEach(el => {
    el.addEventListener("click", e => {
        //Se obtiene el numero dentro del span
        let number = Number(el.lastElementChild.textContent);
        console.log('numero seleccionado ' + number);
        //Se hace la operación para calcular cuanto será el top de elementos a no mostrarse en la consulta en este caso seran 8
        let limit = (number * 8) - 8;
        //Se ejecuta la recarga de datos enviando la variable de topAct
        //Ejecutamos la función para predecir si habrá un boton de adelante
        readRowsLimit(API_PEDIDOE 
, limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }); 
});

/*Funciónes del boton de ir hacia arriba*/
window.onscroll = function () {
    if (document.documentElement.scrollTop > 100) {
        HASTATOP.style.display = "block";
    } else {
        HASTATOP.style.display = "none";
    }
};

HASTATOP.addEventListener('click', function () {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    })
});


//Función para llenar el contenedor de clientes con los datos obtenidos del controlador de components
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += ` 
        <div class="col l3 s12 m6">
        <!--Tarjeta-->
        <div class="card" id="tarjetas-privado">
            <div class="botones">
                <!--Botón eliminar-->
                <!--Botón aceptado-->
                <a onclick= "dePediP(${row.id_pedidos_establecidos})"  class="btn-floating waves-effect waves-light green"><i
                        class="material-icons">visibility</i></a>
            </div>
            <!--Contenido-->
            <div class="imagen-pedidos center-align col s12 m12 s12">
                <img src="../../resources/img/icons/pedidos.png">
                <span class="card-title">
                    <h6>${row.estado}</h6>
            </div>
            <div class="card-content">
                <p>$${row.montototal_pedidoesta}</p>
            </div>
        </div>
    </div>
        `;
    });
     // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
     document.getElementById('pedidos-row').innerHTML = content;
     // Se inicializa el componente Material Box para que funcione el efecto Lightbox.
     M.Materialbox.init(document.querySelectorAll('.materialboxed'));
     // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
     M.Tooltip.init(document.querySelectorAll('.tooltipped'));
 }

 BUSCADOR.addEventListener('keyup',function(e){
    if(BUSCADOR.value == ''){
        readRowsLimit(API_PEDIDOE, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }else{
        // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    dynamicSearcher2(API_PEDIDOE, 'search-form');
    }
});

function noDatos(){
    let h = document.createElement("h3");
    let text = document.createTextNode("0 resultados");
    h.appendChild(text);
    document.getElementById('pedidos-row').innerHTML = '';
    document.getElementById('pedidos-row').append(h);
}

function dePediP(id){
    // Se define un objeto con los datos del registro seleccionado.
    const form = new FormData(); 
    form.append('id_pedidos_establecidos', id);
    fetch(API_PEDIDOE + 'readPedido', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    //Se llenan los campos
                    INPUTDESCRIP.value=response.dataset.descripcionlugar_entrega;
                    INPUTAPELLIDO.value=response.dataset.apellido_cliente;
                    INPUTNOMBRE.value=response.dataset.nombre_cliente;
                    INPUTFECHA.value=response.dataset.fecha_pedidoesta;
                    INPUTNOMTO.value=response.dataset.montototal_pedidoesta;
                    INPUTID.value=id;
                    document.getElementById('direccion').value = response.dataset.direccion_cliente;
                    M.updateTextFields();
                    M.textareaAutoResize(INPUTDESCRIP);
                    M.textareaAutoResize(document.getElementById('direccion'));
                    cargarTable(id);
                    var instance = M.Modal.getInstance(MODALACEPTAR);
                    instance.open();
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
            NOMBREPROD.value = '';
        }
    });
}

function cargarTable(id){
     // Se define un objeto con los datos del registro seleccionado.
    let form = new FormData();
    let content = '';
    form.append('id_pedidos_establecidos', id);
    fetch(API_PEDIDOE + 'obtenerDetalle', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    //Se llenan los campos
                    response.dataset.map(function (row){
                        content += `
                            <tr>
                                <td>${row.nombre_producto}</td>
                                <td>${row.cantidad_detallep}</td>
                                <td>$${row.precio_producto}</td>
                                <td>$${row.subtotal_detallep}</td>
                            </tr>
                        `;
                    });
                    TABLEPEDIDO.innerHTML=content;
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
            NOMBREPROD.value = '';
        }
    });
}

//Función para eliminar un pedido
function dePedP(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_pedidos_establecidos', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js y paso el valor de 8 para recargar los clientes
    confirmDeleteL(API_PEDIDOE, data, 0);
}

//Método del buscador dinámico
BUSCADOR_AMI.addEventListener('keyup',function(e){
    if(BUSCADOR_AMI.value == ''){
    readRowsLimit(API_PEDIDOE, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }else{
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    dynamicSearcher2(API_PEDIDOE, 'buscador_pedido');
    }

});

//Función para verificar si hay una sesion
// Petición para consultar si existen usuarios registrados.
function comprobaClientes() {
    fetch(API_CLIENTESENDPOINT + 'readUsers', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {

                }
                else {
                    location.href = 'index.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Metodos para generar el pdf
function generarPDFP(){
    //Obtenemos el id del pedido
    let params = '?id='+INPUTID.value;
    // Se establece la ruta del reporte en el servidor.
    let url = SERVER + 'reports/dashboard/pedidoEstaPDF.php';
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(url + params);
}