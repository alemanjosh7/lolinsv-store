// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_PRODUCTOS = SERVER + 'dashboard/productos.php?action=';
const ENDPOINT_CATEGORIAS = SERVER + 'dashboard/categorias.php?action=readAll';
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';

//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    comprobarAdmins()
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    // Se inicializa el componente Select del formulario para que muestre las opciones.
    readRowsLimit(API_PRODUCTOS,0);
    BOTONATRAS.style.display = 'none';
    // Se define una variable para establecer las opciones del componente Modal.
    let options = {
        dismissible: false,
        onOpenStart: function () {
            // Se restauran los elementos del formulario.
            document.getElementById('save-form').reset();
            // Se establece el valor mínimo para el precio del producto.
            document.getElementById('precio').setAttribute('min', 0.01);
            // Se establece el valor máximo para el precio del producto.
            document.getElementById('precio').setAttribute('max', 999.99);
        }
    }
    // Se inicializa el componente Modal para que funcionen las cajas de diálogo.
    M.Modal.init(document.querySelectorAll('.modal'), options);
    predecirAdelante();
});
//Declaramos algunos componentes
const HASTATOP = document.getElementById('hasta_arriba');//Boton de hasta arriba
const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras
const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai
const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf
const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante

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
        // Se establece un icono para el estado del producto.
        (row.cantidad_producto) ? icon = 'visibility' : icon = 'visibility_off';
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <div class="input-field col s12 " id="perfil-usuario">
                <div id="boton-producto" class="right-align">
                    <a onclick="openDelete(${row.id_producto})" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">cancel</i></a>
                    <a onclick="openUpdate(${row.id_producto})" href="#modal-Editar"
                    class="modal-trigger btn-floating btn-small waves-effect waves-light black"><i
                    class="material-icons">edit</i></a>
                </div>
                <div class="imagen-perfil center-align col s12 m12 s12">
                    <img class="responsive-image" src="${SERVER}images/productos/${row.imagen_producto}" id="amigurumi-img">
                </div>
                <div class="col s12 center-align" id="div-botoncambiarcontra">
                    <h6>${row.nombre_producto}</h6>
                    <h8>${row.precio_producto}</h8>
                </div>
            </div>
        `;
    });
    document.getElementById('columna1').innerHTML = content;
    // Se inicializa el componente Material Box para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// document.getElementById('search').addEventListener('keypress', (event) => {
//     // Se evita recargar la página web después de enviar el formulario.
//     // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
//     console.log(document.getElementById('search').value);
//     const key = event.keyCode;

//     if (key === 8 || key == 46) {
//         if(cadena.length == 1){
//             readRows(API_PRODUCTOS);
//         }
//         else{
//             searchRows(API_PRODUCTOS, 'search-form');
//         }
//     }
//     else {
//         searchRows(API_PRODUCTOS, 'search-form');
//         cadena = this.value;
//     }
// });


document.getElementById('search').addEventListener('keyup', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    if (document.getElementById('search').value === "") {
        readRows(API_PRODUCTOS);
    }
    else {
        dynamicSearcher(API_PRODUCTOS, 'search-form');
    }

});

/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}

/*Ejemplo de la consulta para la navegación
select * from productos where id_producto
not in(select id_producto from productos order by id_producto limit 4) order by id_producto limit 4;

donde el limit dentro del not in sería la variable de pagNavegm
*/
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDeleteL(API_PRODUCTOS, data,0);
}

function openCreate() {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    // Se establece el campo de archivo como obligatorio.
    document.getElementById('modal-title').textContent = 'Crear producto';
    document.getElementById('archivo').required = true;
    document.getElementById('reiniciarfomr').classList.remove("hide");
    var id_producto = document.getElementById('input-id');
    id_producto.style.display = 'none';
    id_producto.style.visibility = 'hidden';
    M.FormSelect.init(document.querySelectorAll('select'));
    var input_rating = document.getElementById('rating-input');
    input_rating.style.display = 'none';
    input_rating.style.visibility = 'hidden';
    // Se llama a la función que llena el select del formulario. Se encuentra en el archivo components.js
    fillSelect(ENDPOINT_CATEGORIAS, 'categoria', null);
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id) {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    // Se establece el campo de archivo como opcional.

    document.getElementById('archivo').required = false;
    document.getElementById('reiniciarfomr').classList.add("hide");
    var input_rating = document.getElementById('rating-input');
    document.getElementById('modal-title').textContent = 'Editar producto';
    input_rating.style.display = 'block';
    input_rating.style.visibility = 'visible';
    var id_producto = document.getElementById('input-id');
    id_producto.style.display = 'block';
    id_producto.style.visibility = 'visible';

    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_PRODUCTOS + 'readOne', {
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
                    document.getElementById('id').value = response.dataset.id_producto;
                    document.getElementById('idp').value = response.dataset.id_producto;
                    document.getElementById('nombre').value = response.dataset.nombre_producto;
                    document.getElementById('precio').value = response.dataset.precio_producto;
                    document.getElementById('descripcion').value = response.dataset.descripcion;
                    document.getElementById('cantidad').value = response.dataset.cantidad;
                    fillSelect(ENDPOINT_CATEGORIAS, 'categoria', response.dataset.fk_id_categoria);
                    document.getElementById('rating').value = response.dataset.fk_id_valoraciones;
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

document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
    (document.getElementById('id').value) ? action = 'update' : action = 'create';
    console.log(action);
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRowL(API_PRODUCTOS, action, 'save-form', 'modal-Editar',0);
});

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
    predictLImit(API_PRODUCTOS, limit);
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
        let limit = (number * 12) - 12;
        //Se ejecuta la recarga de datos enviando la variable de topAct
        //Ejecutamos la función para predecir si habrá un boton de adelante
        readRowsLimit(API_PRODUCTOS, limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    });
});