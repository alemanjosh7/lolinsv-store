// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_COMENTARIOCL = SERVER + 'dashboard/comentarioscl.php?action=';

//Iniciando algunos componentes y funciones
document.addEventListener('DOMContentLoaded', function () {
    //Inicializamos los componentes de Materialize
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    //Inicializamos los metodos
    comprobarAdmins();//Comprobamos si hay admins 
    //Ocultamos el boton de atras para la páginación
    BOTONATRAS.style.display = 'none';
    //Ejecutamos la función para predecir si habrá un boton de adelante
    predecirAdelante();
    readRowsLimit(API_COMENTARIOCL, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    //Ocultamos el boton de atras para la páginación
    BOTONATRAS.style.display = 'none';
});

//Declarando algunos componentes
const HASTATOP = document.getElementById('hasta_arriba');//Boton de hasta arriba
const PRELOADER = document.getElementById('preloader-cl');//Preloader de carga
const COMCONT = document.getElementById('tbody-rows');//Contenedor del contenido de los comentarios
const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras
const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai
const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf
const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante

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
                    location.href = 'primeruso.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Función para llenar el contenedor de clientes con los datos obtenidos del controlador de components
function fillTable(dataset) {
    let content = '';
    PRELOADER.style.display = 'block';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr class="margen">
                <td>${row.usuario}</td>
                <td>${row.nombre_producto}</td>
                <td><img class="responsive-img" src="../../api/images/calificaciones/${row.fk_id_valoraciones}.png" height="50px" width="50px"></td>
                <td class="parrafo-r"><p>${row.comentario}</p></td>
                <td>
                    <a onclick="delCom(${row.id_valoracionescli})" class="btn-floating waves-effect red tooltipped" data-tooltip="Eliminar">
                        <i class="material-icons">delete</i>
                    </a>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    COMCONT.innerHTML = content;
    PRELOADER.style.display = 'none';
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

//Funciones para la páginación

//Función para saber si hay otra página
function predecirAdelante() {
    //Colocamos el boton con un display block para futuras operaciones
    BOTONADELANTE.style.display = 'block';
    //Obtenemos el número de página que seguiría al actual
    let paginaFinal = (Number(BOTONNUMEROPAGF.innerHTML)) + 2;
    console.log("pagina maxima " + paginaFinal);
    //Calculamos el limite que tendria el filtro de la consulta dependiendo de la cantidad de Clientes a mostrar
    let limit = (paginaFinal * 5) - 5;
    console.log("El limite sería: " + limit);
    //Ejecutamos el metodo de la API para saber si hay productos y esta ejecutará una función que oculte o muestre el boton de adelante
    predictLImit(API_COMENTARIOCL, limit);
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
        readRowsLimit(API_COMENTARIOCL, limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    });
});

//Función de eliminar
function delCom(id){
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js y paso el valor de 8 para recargar los clientes
    confirmDeleteL(API_COMENTARIOCL, data, 0);
}

//Metodo del buscador

const BUSCADORBTN = document.getElementById("buscador-btn");

BUSCADORBTN.addEventListener('click',function(){
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_COMENTARIOCL, 'buscador-form');
});

const  BUSCADORINP = document.getElementById("search");

BUSCADORINP.addEventListener('keyup',function(e){
    if(BUSCADORINP.value == ''){
        readRowsLimit(API_COMENTARIOCL, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }else{
        // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    dynamicSearcher2(API_COMENTARIOCL, 'buscador-form');
    }
});

function noDatos(){
    let h = document.createElement("h3");
    let text = document.createTextNode("0 resultados"); 
    h.appendChild(text);
    CLIENTESCONTENEDOR.innerHTML = "";
    CLIENTESCONTENEDOR.append(h);
}