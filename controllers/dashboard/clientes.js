// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_CLIENTES = SERVER + 'dashboard/clientes.php?action=';
//Iniciando algunos componentes y funciones
document.addEventListener('DOMContentLoaded', function () {
    //Inicializamos los componentes de Materialize
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Modal.init(document.querySelectorAll('.modal'));
    //Inicializamos los metodos
    comprobarAdmins();//Comprobamos si hay admins
    readRowsLimit(API_CLIENTES, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio   
    //Ocultamos el boton de atras para la páginación
    BOTONATRAS.style.display = 'none';
    //Ejecutamos la función para predecir si habrá un boton de adelante
    predecirAdelante();
});

//Declarando algunos componentes
const PRELOADER = document.getElementById('preloader-cl');//Preloader de carga
const TEXTOESTADO = document.getElementsByClassName('estado-cl');//Texto que indica el estado del cliente
const CLIENTESCONTENEDOR = document.getElementById('clientes-row');
const HASTATOP = document.getElementById('hasta_arriba');//Boton de hasta arriba
const BOTONATRAS = document.getElementById("pagnavg-atr");
const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");
const BOTONNUMEROPAGF = document.getElementById("pagnumerof");
const BOTONADELANTE = document.getElementById("pagnavg-adl");

//Función que cambia el color del texto del estado del cliente en caso
function analizarEstado() {
    for (var i = 0; i < TEXTOESTADO.length; i++) {
        //Obtenemos los componentes individuales
        var componente = document.getElementsByClassName('estado-cl')[i];
        //Obtenemos el texto dentro de cada uno
        var texto = componente.childNodes[0].textContent;
        //Evaluamos si dice Bloqueado o Activo
        if (texto == 'Bloqueado') {
            componente.style.color = 'Red';
        } else {
            componente.style.color = 'Green';
        }
    }
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
    PRELOADER.style.display = 'block';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <div class="col l3 s12 m6">
                <!--Tarjeta clientes-->
                <div class="card" id="tarjetas-privado">
                    <!--Botones  de la tarjeta-->
                    <div class="botones">
                        <!--Boton borrar-->
                        <a class="modal-trigger btn-floating waves-effect waves-light red" id=""><i
                            class="material-icons tooltipped" data-position="left" data-tooltip="Eliminar CLiente">delete</i></a>
                        <!--Boton editar-->
                        <a class="btn-floating waves-effect waves-grey lighten-1"><i
                            class="material-icons tooltipped" data-position="right" data-tooltip="Modificar CLiente">edit</i></a>
                    </div>
                    <!--Imagen de la tarjeta-->
                    <div class="imagen-cliente center-align col s12 m12 s12">
                        <img src="../../resources/img/icons/UserIcon-Perfil.png">
                        <!--Contenido de la tarjeta-->
                        <span class="card-title">
                        <h6>${row.nombre_cliente}${' '}${row.apellido_cliente}</h6>
                        </span>
                    </div>
                    <div class="card-content">
                        <p class="estado-cl">${row.estado}</p>
                    </div>
                </div>
            </div>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    CLIENTESCONTENEDOR.innerHTML = content;
    PRELOADER.style.display = 'none';
    analizarEstado();
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

//Funciones para la páginación

//Función para saber si hay otra página
function predecirAdelante(){
    //Colocamos el boton con un display block para futuras operaciones
    BOTONADELANTE.style.display = 'block';
    //Obtenemos el número de página que seguiría al actual
    let paginaFinal = (Number(BOTONNUMEROPAGF.innerHTML))+2;
    console.log("pagina maxima "+paginaFinal);
    //Calculamos el limite que tendria el filtro de la consulta dependiendo de la cantidad de Clientes a mostrar
    let limit = (paginaFinal*8)-8;
    console.log("El limite sería: "+limit);
    //Ejecutamos el metodo de la API para saber si hay productos y esta ejecutará una función que oculte o muestre el boton de adelante
    predictLImit(API_CLIENTES,limit);
}

function ocultarMostrarAdl(result){
    if(result != true){
        console.log('Se oculta el boton');
        BOTONADELANTE.style.display = 'none';
    }else{
        //Colocamos el boton con un display block para futuras operaciones
        console.log('Se muestra el boton');
        BOTONADELANTE.style.display = 'block';
    }
}

//Boton de atras
BOTONATRAS.addEventListener('click',function(){
    //Volvemos a mostrár el boton de página adelante
    BOTONADELANTE.style.display = 'block';
    //Obtenemos el número de la página inicial
    let paginaActual = Number(BOTONNUMEROPAGI.textContent);
    //Comprobamos que el número de página no sea igual a 1
    if(paginaActual!=1){
        //Restamos la cantidad de páginas que queramos que se retroceda en este caso decidi 2 para el botoni y 1 para el botonf
        BOTONNUMEROPAGI.innerHTML=Number(BOTONNUMEROPAGI.innerHTML)-2;
        BOTONNUMEROPAGF.innerHTML=Number(BOTONNUMEROPAGI.innerHTML)+1;
        //Verificamos si el número del boton ahora es 1, en caso lo sea se ocultará el boton
        if((Number(BOTONNUMEROPAGI.innerHTML)-1)==0){
            BOTONATRAS.style.display = 'none';
        }
    }
});

//Boton de adelante
BOTONADELANTE.addEventListener('click',function(){
    //Volvemos a mostrár el boton de página anterior
    BOTONATRAS.style.display = 'block';
    //Ejecutamos la función para predecir si hay más páginas
    predecirAdelante();
    //Luego verificamos si el boton de adelante aun continua mostrandose
    if(BOTONADELANTE.style.display = 'block'){
        //Sumamos la cantidad de página que queramos que avance, en este caso decidi 2 para el botoni y 3 para el botonf
        BOTONNUMEROPAGI.innerHTML=Number(BOTONNUMEROPAGI.innerHTML)+2;
        BOTONNUMEROPAGF.innerHTML=Number(BOTONNUMEROPAGI.innerHTML)+1;
    }
});

//Función que realizará los botones con numero de la páginacion
document.querySelectorAll(".contnpag").forEach(el => {
    el.addEventListener("click", e => {
        //Se obtiene el numero dentro del span
        let number =Number(el.lastElementChild.textContent);
        console.log('numero seleccionado '+number);
        //Se hace la operación para calcular cuanto será el top de elementos a no mostrarse en la consulta en este caso seran 8
        let limit = (number*8)-8;
        //Se ejecuta la recarga de datos enviando la variable de topAct
        //Ejecutamos la función para predecir si habrá un boton de adelante
        readRowsLimit(API_CLIENTES, limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    });
});