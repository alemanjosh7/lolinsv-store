// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_PRODUCTOS = SERVER + 'dashboard/productos.php?action=';

//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    M.Modal.init(document.querySelectorAll('.modal'));
    botonAdelante.style.display = 'block';

    readRows(API_PRODUCTOS);
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
                    <a onclick="openUpdate(${row.id_producto})" class="btn-floating btn-small waves-effect waves-light black"><i class="material-icons">edit</i></a>
                </div>
                <div class="imagen-perfil center-align col s12 m12 s12">
                    <img class="responsive-image" src="${SERVER}images/productos/${row.imagen_producto}.png" id="amigurumi-img">
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
    const key = event.keyCode;
    if (key === 8 || key == 46) {
        console.log(document.getElementById('search-form'));
        if (document.getElementById('search').value === "") {
            readRows(API_PRODUCTOS);
        }
        else {
            searchRows(API_PRODUCTOS, 'search-form');
        }
    }
    else {
        searchRows(API_PRODUCTOS, 'search-form');
    }

});

/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}


document.querySelectorAll(".pagnavg").forEach(el => {
    el.addEventListener("click", e => {
        //Se obtiene el numero dentro del span
        let number = Number(el.textContent);
        //Se hace la operación para calcular cuanto será el top de elementos a no mostrarse en la consulta
        let topAct = (number * 4) - 4;
        console.log("Se ha clickeado el id " + number + " " + topAct);
        //Se ejecuta la recarga de datos enviando la variable de topAct
    });
});

//Evento cuando se precione el boton de ir atras y adelante en la páginación
var botonAtras = document.getElementById("pagnavg-atr");
var botonNumeroPagI = document.getElementById("pagnumeroi");
var botonNumeroPagF = document.getElementById("pagnumerof");
var botonAdelante = document.getElementById("pagnavg-adl");

//Boton de atras
botonAtras.addEventListener('click', function () {
    let paginaActual = Number(botonNumeroPagI.textContent);
    if (paginaActual != 1) {
        botonNumeroPagI.innerHTML = Number(botonNumeroPagI.innerHTML) - 2;
        botonNumeroPagF.innerHTML = Number(botonNumeroPagI.innerHTML) + 1;
        if ((Number(botonNumeroPagI.innerHTML) - 1) == 0) {
            botonAtras.style.display = 'none';
        }
    } else {
    }
});

//Boton de adelante
botonAdelante.addEventListener('click', function () {
    botonAtras.style.display = 'block';
    let paginaFinal = (Number(botonNumeroPagF.innerHTML)) + 1;
    console.log("pagina maxima " + paginaFinal);
    /*Aqui se debe obtener si hay más datos o no, pueden usar una función que puede devolver un valor booleano
    deben realizar una operación sumando a la pagina final mostrada la cantidad de elementos que muestran y enviarlos para comprobar
    si hay datos entonces la variable será true, si no hay más datos entonces no se mostrará nada
    */
    let topAct = (paginaFinal * 4) - 4;
    console.log("el top a no mostrar sería " + paginaFinal)
    masDatos = true;

    if (botonAdelante.style.display != 'none') {
        console.log('no se esta mostrando')
    }

    /*if(botonAdelante.style.display != 'block'){
        botonNumeroPagI.innerHTML=Number(botonNumeroPagI.innerHTML)+2;
        botonNumeroPagF.innerHTML=Number(botonNumeroPagI.innerHTML)+1;
    }else{

    }*/
});

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
    confirmDeleteL(API_PRODUCTOS, data);
}