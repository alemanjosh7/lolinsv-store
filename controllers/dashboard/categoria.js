// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
const API_CATEGORIAS = SERVER + 'dashboard/categorias.php?action=';


// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    comprobarAdmins();//Comprobamos si hay admins
    predecirAdelante();//
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRowsLimit(API_CATEGORIAS,0);
    // Se define una variable para establecer las opciones del componente Modal.
    let options = {
        dismissible: false,
        onOpenStart: function () {
            // Se restauran los elementos del formulario.
            document.getElementById('save-form').reset();
        }
    }
    // Se inicializa el componente Modal para que funcionen las cajas de diálogo.
    M.Modal.init(document.querySelectorAll('.modal'),options);
    //Ocultamos el boton de atras para la páginación
    BOTONATRAS.style.display = 'none';
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
});


//Declarando algunos componentes
const HASTATOP = document.getElementById('hasta_arriba');//Boton de hasta arriba

const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras

const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai

const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf

const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante


//Funciones para la páginación
//Función para saber si hay otra página
function predecirAdelante() {

    //Colocamos el boton con un display block para futuras operaciones

    BOTONADELANTE.style.display = 'block';

    //Obtenemos el número de página que seguiría al actual

    let paginaFinal = (Number(BOTONNUMEROPAGF.innerHTML)) + 2;

    console.log("pagina maxima " + paginaFinal);

    //Calculamos el limite que tendria el filtro de la consulta dependiendo de la cantidad de Clientes a mostrar

    let limit = (paginaFinal * 12) - 12;

    console.log("El limite sería: " + limit);

    //Ejecutamos el metodo de la API para saber si hay productos y esta ejecutará una función que oculte o muestre el boton de adelante

    predictLImit(API_CATEGORIAS, limit);
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

        let limit = (number * 12) - 12;

        //Se ejecuta la recarga de datos enviando la variable de topAct

        //Ejecutamos la función para predecir si habrá un boton de adelante

        readRowsLimit(API_CATEGORIAS
, limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }); 
});

//Función que realizará los botones con numero de la páginacion

document.querySelectorAll(".contnpag").forEach(el => {

    el.addEventListener("click", e => {

        //Se obtiene el numero dentro del span

        let number = Number(el.lastElementChild.textContent);

        console.log('numero seleccionado ' + number);

        //Se hace la operación para calcular cuanto será el top de elementos a no mostrarse en la consulta en este caso seran 8

        let limit = (number * 12) - 12;

        //Se ejecuta la recarga de datos enviando la variable de topAct

        //Ejecutamos la función para predecir si habrá un boton de adelante

        readRowsLimit(API_CATEGORIAS
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

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
        <div  class"row">
             <div class="input-field col s12 m12 l4">
                <input disabled value="${row.nombre_categoria}" id="disabled" type="text" class="validate">
                <label for="last_name"></label>
                <a onclick="openDelete(${row.id_categoria})"class="btn-floating waves-effect waves-light red eliminarbtn"><i
                class="material-icons tooltipped" data-position="left" data-tooltip="Eliminar Categoria">delete</i></a>
                <a onclick="openUpdate(${row.id_categoria})" class="btn-floating waves-effect black waves-grey lighten-1 modbtn"><i
                            class="material-icons tooltipped" data-position="right" data-tooltip="Modificar Categoria">edit</i></a>
              </div>
        </div>   
        `;
    });

    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('Categorias').innerHTML = content;
    // Se inicializa el componente Material Box para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

//Función para eliminar una categorias
function openDelete(id_categoria) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_categoria', id_categoria);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js y paso el valor de 8 para recargar los clientes
    confirmDeleteL(API_CATEGORIAS, data, 0);
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

                    location.href = 'primeruso.html';

                }

            });

        } else {

            console.log(request.status + ' ' + request.statusText);

        }

    });

}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_CATEGORIAS, 'search-form');
});



// Función para preparar el formulario al momento de insertar un registro.
function openCreate() {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('confirmar-compra_modal')).open();
    // Se asigna el título para la caja de diálogo (modal).
    // Se establece el campo de archivo como obligatorio.
    document.getElementById('nombre').required = true;
}


// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id_categoria) {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('confirmar-compra_modal')).open();
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('title').textContent = 'Actualizar categoría';
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_categoria', id_categoria);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_CATEGORIAS + 'readACategory', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id_categoria').value = response.dataset.id_categoria;
                    document.getElementById('nombre').value = response.dataset.nombre_categoria;
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
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
    (document.getElementById('id_categoria').value) ? action = 'update' : action = 'create';
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRowL(API_CATEGORIAS, action, 'save-form', 'confirmar-compra_modal', 0);
});

BUSCADOR.addEventListener('keyup',function(e){
    if(BUSCADOR.value == ''){
        readRowsLimit(API_CATEGORIAS, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }else{
        // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    dynamicSearcher2(API_CATEGORIAS, 'search-form');
    }
});

function noDatos(){
    let h = document.createElement("h3");
    let text = document.createTextNode("0 resultados");
    h.appendChild(text);
    document.getElementById('Categorias').innerHTML = '';
    document.getElementById('Categorias').append(h);
}

