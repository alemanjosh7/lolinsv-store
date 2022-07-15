// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_INVENTARIO = SERVER + 'dashboard/inventario.php?action=';

//Opciones para el modal de añadir
var opcionesM = {
    onCloseEnd: function () {
        // Se restauran los elementos del formulario.
        document.getElementById('aminventario-form').reset();
        CODPRODUCTO.removeAttribute("disabled", "");
        CANTIDADN.removeAttribute("disabled", "");
        CANTIDADA.setAttribute("disabled", "");
        GUARDARBTN.classList.remove('disabled');
        ACTPRELOADER.style.display = 'none';
        MENSAJE.style.display = 'none';
        let url = SERVER + 'images/producto-icono.png';
        IMGPROD.setAttribute('src', url);
        IMGPROD.setAttribute('data-caption', "Selecciona un producto para ver su descripcion");
        M.updateTextFields();
    }
}

//Iniciando algunos componentes y funciones
document.addEventListener('DOMContentLoaded', function () {
    //Inicializamos los componentes de Materialize
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Modal.init(document.querySelectorAll('.modal'), opcionesM);
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    //Inicializamos los metodos
    comprobarAdmins();//Comprobamos si hay admins 
    //Ocultamos el boton de atras para la páginación
    BOTONATRAS.style.display = 'none';
    //Ejecutamos la función para predecir si habrá un boton de adelante
    predecirAdelante();
    readRowsLimit(API_INVENTARIO, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
});

//Declarando algunos componentes
const HASTATOP = document.getElementById('hasta_arriba');//Boton de hasta arriba
const PRELOADER = document.getElementById('preloader-cl');//Preloader de carga
const COMCONT = document.getElementById('tbody-rows');//Contenedor del contenido de los comentarios
const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras
const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai
const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf
const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante
const MODALAM = document.getElementById("modalam");//Modal de añadir
const CODPRODUCTO = document.getElementById("producto");//Input del codigo del producto
const CODPRODUCTOH = document.getElementById("productoh");//Input del codigo del producto
const CANTIDADA = document.getElementById("cantidadas");//Input de la cantidad actual del producto
const CANTIDADN = document.getElementById("cantidadn");//Input de la cantidad nueva del producto
const IMGPROD = document.getElementById("imagenprod");//Imagen del producto
const GUARDARBTN = document.getElementById("guardarReg");//Boton de guardado
const IDINV = document.getElementById("idinventario");
const NOMBREPROD = document.getElementById("productonames");//Nombre del producto
const NOMBREPRODH = document.getElementById("productoname");
const CANTIDADAH = document.getElementById("cantidada");
const ACTPRELOADER = document.getElementById("actdatoscontra_preloader");
const MENSAJE = document.getElementById("mensaje-restablecer");
const ENCABEZADO = document.getElementById('encabezado-modal');
const BUSCADOR = document.getElementById('icon_prefix');
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
                <td>${row.nombre_admin}</td>
                <td>${row.apellido_admin}</td>
                <td>${row.nombre_producto}</td>
                <td>${row.fecha}</td>
                <td>${row.cantidada}</td>
                <td>${row.cantidadn}</td>
                <td>
                    <a onclick="modInv(${row.id_inventario},${row.modificado},'${row.fecha}',${row.fk_id_producto},${row.cantidada})" 
                    class="btn-floating waves-effect black tooltipped" data-tooltip="Modificar">
                        <i class="material-icons">edit</i>
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
    predictLImit(API_INVENTARIO, limit);
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
        let limit = (number * 5) - 5;
        //Se ejecuta la recarga de datos enviando la variable de topAct
        //Ejecutamos la función para predecir si habrá un boton de adelante
        readRowsLimit(API_INVENTARIO, limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
        //Ejecutamos la función para predecir si hay más páginas
        predecirAdelante();
    });
});

//Validar solo numeros en la cantidad y el codigo del producto
CODPRODUCTO.addEventListener('keypress', function (e) {
    if (!soloNumeros(event, 1)) {
        e.preventDefault();
    }
});

CANTIDADN.addEventListener('keypress', function (e) {
    if (!soloNumeros(event, 1)) {
        e.preventDefault();
    }
});

//Función para cargar la imagen del producto al teclear el id
CODPRODUCTO.addEventListener('keyup', function () {
    if (CODPRODUCTO.value != '') {
        cargarImg(CODPRODUCTO.value)
    } else {
        let url = SERVER + 'images/producto-icono.png';
        IMGPROD.setAttribute('src', url);
        IMGPROD.setAttribute('data-caption', "Selecciona un producto para ver su descripcion");
        NOMBREPROD.value = '';
        CANTIDADA.value = '';
        M.updateTextFields();
    }
})

//Metodo para eliminar
function delInv(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js y paso el valor de 8 para recargar los clientes
    confirmDeleteL(API_INVENTARIO, data, 0);
}

//Metodo para crear un registro del inventario
function anadirinv() {
    //Se muestra el modal
    ENCABEZADO.innerText = 'Añadir inventario';
    M.Modal.getInstance(MODALAM).open();
}

function cargarImg(id) {
    let form = new FormData();
    form.append('id', id);
    fetch(API_INVENTARIO + 'buscarImg', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    let url = SERVER + 'images/productos/' + response.dataset.imagen_producto;
                    IMGPROD.setAttribute('src', url);
                    IMGPROD.setAttribute('data-caption', response.dataset.descripcion_producto);
                    NOMBREPROD.value = response.dataset.nombre_producto;
                    CANTIDADA.value = response.dataset.cantidad;
                    CANTIDADAH.value = response.dataset.cantidad;
                    NOMBREPRODH.value = response.dataset.nombre_producto;
                    M.updateTextFields();
                } else {
                    let url = SERVER + 'images/producto-icono.png';
                    IMGPROD.setAttribute('src', url);
                    IMGPROD.setAttribute('data-caption', "Selecciona un producto para ver su descripcion");
                    NOMBREPROD.value = '';
                    CANTIDADA.value = '';
                    M.updateTextFields();
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
            NOMBREPROD.value = '';
        }
    });
}

GUARDARBTN.addEventListener('click', function () {
    //Validamos campos vacios
    let arreglo = [CODPRODUCTO, CANTIDADN];
    if (validarCamposVacios(arreglo) != false) {
        if (CANTIDADN.value > 0) {
            MENSAJE.style.display = 'none';
            let action = '';
            //Calculamos la nueva cantidad a actualizar del producto
            let cantidadact = parseInt(CANTIDADA.value)+parseInt(CANTIDADN.value);
            //la agregamos a un campo
            let cantidad = document.getElementById('cantidadact');
            cantidad.value = cantidadact;
            // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
            (document.getElementById('idinventario').value) ? action = 'update' : action = 'create';
            saveRowL(API_INVENTARIO, action, 'aminventario-form', 'modalam', 0);
            GUARDARBTN.classList.add('disabled');
            ACTPRELOADER.style.display = 'block';
        } else {
            MENSAJE.innerHTML = 'La cantidad a ingresar debe ser mayor a 1';
            MENSAJE.style.display = 'block';
        }
    } else {
        MENSAJE.innerText = 'No se permiten campos vacios';
        MENSAJE.style.display = 'block';
    }
});

//metodo para actualizar
function modInv(id, mod, fecha, idprd, cantidada) {
    //Evaluamos si el registro ya ha sido modificado
    if (mod != true) {
        //Verificamos si no ha pasado un día desde la creación del registro
        let hoy = new Date().getTime();
        let fechai = new Date(fecha).getTime();
        let diff = hoy - fechai;
        let dia = Math.floor(diff / (1000 * 60 * 60 * 24));
        if (dia <= 0) {
            IDINV.value = id;
            //Se cambia el encabezado
            ENCABEZADO.innerText = 'Modificar inventario';
            //Se muestra el preloader de carga
            PRELOADER.style.display = 'block';
            //Se carga la imagen en base al producto seleccionado
            cargarImgAct(idprd);
            //se desavilita el input del codigo del producto, de esta forma no se podrá cambiar
            CODPRODUCTOH.value = idprd;
            CODPRODUCTO.value = idprd;
            CANTIDADA.value = cantidada;
            CANTIDADAH.value = cantidada;
            M.Modal.getInstance(MODALAM).open();
            //Se oculta el preloader de carga
            PRELOADER.style.display = 'none';
            CODPRODUCTO.setAttribute("disabled", "");
        } else {
            sweetAlert(4, 'El tiempo para corregir el registro ha caducado, si desea cambiar la cantidad debe ir a la pestaña de productos');
        }
    } else {
        sweetAlert(4, 'El registro ya ha sido modificado una vez, si desea cambiar la cantidad debe ir a la pestaña de productos');
    }
}

function cargarImgAct(id) {
    let form = new FormData();
    form.append('id', id);
    fetch(API_INVENTARIO + 'buscarImg', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    let url = SERVER + 'images/productos/' + response.dataset.imagen_producto;
                    IMGPROD.setAttribute('src', url);
                    IMGPROD.setAttribute('data-caption', response.dataset.descripcion_producto);
                    NOMBREPROD.value = response.dataset.nombre_producto;
                    NOMBREPRODH.value = response.dataset.nombre_producto;
                    M.updateTextFields();
                } else {
                    let url = SERVER + 'images/producto-icono.png';
                    IMGPROD.setAttribute('src', url);
                    IMGPROD.setAttribute('data-caption', "Selecciona un producto para ver su descripcion");
                    NOMBREPROD.value = '';
                    CANTIDADA.value = '';
                    M.updateTextFields();
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
            NOMBREPROD.value = '';
        }
    });
}

BUSCADOR.addEventListener('keyup',function(e){
    if(BUSCADOR.value == ''){
        readRowsLimit(API_INVENTARIO, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }else{
        console.log(BUSCADOR.value)
        // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    dynamicSearcher2(API_INVENTARIO, 'buscador-form');
    }
});

function noDatos(){
    let h = document.createElement("h3");
    let text = document.createTextNode("0 resultados"); 
    h.appendChild(text);
    COMCONT.innerHTML = "";
    COMCONT.append(h);
}

//Funciones  para reportes
function obtenerFechasR() {
    M.updateTextFields();
    (async () => {

        const { value: formValues } = await Swal.fire({
            background: '#F7F0E9',
            confirmButtonColor: 'black',
            showDenyButton: true,
            denyButtonText: '<i class="material-icons">cancel</i> Cancelar',
            icon: 'info',
            title: 'Indique las fechas para el reporte, en formato YY-M-D',
            html:
                `   
                <div class="input-field">
                    <label for="swal-input1"><b>Fecha Inicial</b></label>
                    <input type="date" placeholder="Fecha inicial" id="swal-input1" class="center">
                </div>
                <div class="input-field">
                    <label for="swal-input2"><b>Fecha Final</b></label>
                    <input type="date" placeholder="Fecha Final" id="swal-input2" class="center">
                </div>
            `,
            focusConfirm: false,
            confirmButtonText:
                '<i class="material-icons">assignment</i> Generar reporte',
            preConfirm: () => {
                return [
                    document.getElementById('swal-input1').value,
                    document.getElementById('swal-input2').value
                ]
            }
        })

        if (formValues) {
            //Swal.fire(JSON.stringify(formValues[0]))
            let params = '?fechai=' + formValues[0] + '&fechaf=' + formValues[1];
            // Se establece la ruta del reporte en el servidor.
            let url = SERVER + 'reports/dashboard/inventarioFx.php';
            // Se abre el reporte en una nueva pestaña del navegador web.
            window.open(url + params);
            console.log(params);
        }
    })()
}