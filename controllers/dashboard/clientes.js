// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_CLIENTES = SERVER + 'dashboard/clientes.php?action=';

//Variable de opciones para el modal de actualización de cliente
var opcionesClienteM = {
    onOpenStart: function () {
        //Reiniciamos el formulario
        document.getElementById('actcliente-form').reset();
        M.updateTextFields();
        BTNACTUALIZAR.classList.remove('disabled');
        CAMBIARCTRBTN.classList.remove('disabled');
    }
}

//Variable de opciones para el modal de restablecer actualización de cliente
var opcionesRestablecerC = {
    onOpenStart: function () {
        document.getElementById('renovarcontr-form').reset();
        M.updateTextFields();
    }
}

//Iniciando algunos componentes y funciones
document.addEventListener('DOMContentLoaded', function () {
    //Inicializamos los componentes de Materialize
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Modal.init(document.querySelectorAll('#modalact'), opcionesClienteM);
    M.Modal.init(document.querySelectorAll('#modalrestablecer'), opcionesRestablecerC);
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
const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras
const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai
const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf
const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante
const MODALACT = document.getElementById("modalact");//Modal de actualización de cliente
const MODALREC = document.getElementById("modalrestablecer");//Modal de cambiar contraseña
const CAMBIARCTRBTN = document.getElementById("cambiarcontra");//Cambiar Contraseña
const IDCLI = document.getElementById("id_cliente");//Input del id del cliente
const NOMBRECL = document.getElementById('nombreCl');//Input del nombre del cliente
const APELLIDOCL = document.getElementById("apellidoCl");//Input del apellido del cliente
const CORREOCL = document.getElementById("correoCl");//Input del correo del cliente
const DUICL = document.getElementById("duiCl");//Input del dui del cliente
const TELCL = document.getElementById("telefonoCl");//Input del telefono del cliente
const USUARIOCL = document.getElementById("usuarioCl");//Input del usuario del cliente
const DIRECCL = document.getElementById("direccionCL");//Input del direccion del cliente
const ESTADOCL = document.getElementById("estadoCl");//Input del direccion del cliente
const PRELOADERACT = document.getElementById("actdatoscl_preloader");//Preloader de actualización
const MENSAJEACT = document.getElementById("mensajeact");//Mensaje de actualización del cliente
const CONTRAN = document.getElementById('contraseña_nueva');//input de la contraseña nueva en restablecer contraseña
const CONTRAC = document.getElementById('contraseña_confirma');//input de la confirmación de la contraseña en restablecer contraseña
const preloaderC = document.getElementById('actdatoscontra_preloader');//preloader de la actualización de contraseña
const RESTABLECERCTR = document.getElementById('restablecerContraseña');//boton de restablecer contraseña
const OJO2 = document.getElementById('ocultarmostrar_contraseñas');
const BTNACTUALIZAR = document.getElementById('confirmar-act');//Boton de actualización de cliente

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
                        <a onclick="delCli(${row.id_cliente})" class="btn-floating waves-effect waves-light red eliminarbtn"><i
                            class="material-icons tooltipped" data-position="left" data-tooltip="Eliminar CLiente">delete</i></a>
                        <!--Boton editar-->
                        <a onclick="actCli(${row.id_cliente})" class="btn-floating waves-effect waves-grey lighten-1 modbtn"><i
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
function predecirAdelante() {
    //Colocamos el boton con un display block para futuras operaciones
    BOTONADELANTE.style.display = 'block';
    //Obtenemos el número de página que seguiría al actual
    let paginaFinal = (Number(BOTONNUMEROPAGF.innerHTML)) + 2;
    console.log("pagina maxima " + paginaFinal);
    //Calculamos el limite que tendria el filtro de la consulta dependiendo de la cantidad de Clientes a mostrar
    let limit = (paginaFinal * 1) - 1;
    console.log("El limite sería: " + limit);
    //Ejecutamos el metodo de la API para saber si hay productos y esta ejecutará una función que oculte o muestre el boton de adelante
    predictLImit(API_CLIENTES, limit);
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
        let limit = (number * 1) - 1;
        //Se ejecuta la recarga de datos enviando la variable de topAct
        //Ejecutamos la función para predecir si habrá un boton de adelante
        readRowsLimit(API_CLIENTES, limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    });
});

//Función para eliminar un cliente
function delCli(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js y paso el valor de 8 para recargar los clientes
    confirmDeleteL(API_CLIENTES, data, 0);
}

//Función para actualizar un cliente
function actCli(id) {
    //Se muestra el cargador
    PRELOADER.style.display = 'block';
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_CLIENTES + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    //Se muestra el modal
                    M.Modal.getInstance(MODALACT).open();
                    //Se llenan los campos
                    IDCLI.value = response.dataset.id_cliente;
                    NOMBRECL.value = response.dataset.nombre_cliente;
                    APELLIDOCL.value = response.dataset.apellido_cliente;
                    CORREOCL.value = response.dataset.correo_cliente;
                    DUICL.value = response.dataset.dui_cliente;
                    TELCL.value = response.dataset.telefono_cliente;
                    USUARIOCL.value = response.dataset.usuario;
                    DIRECCL.value = response.dataset.direccion_cliente;
                    //Analizamos si el estado es Activo o Bloqueado
                    if (response.dataset.fk_id_estado == 8) {
                        ESTADOCL.checked = true;
                    } else {
                        ESTADOCL.checked = false;
                    }
                    M.updateTextFields();
                    M.textareaAutoResize(DIRECCL);
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

//Función para abrir el modal de cambiar contraseña
CAMBIARCTRBTN.addEventListener('click', function () {
    M.Modal.getInstance(MODALACT).close();
    M.Modal.getInstance(MODALREC).open();
});

//Funcion para restablecer la contraseña de un cliente
RESTABLECERCTR.addEventListener('click', function () {
    /*Creamos una variable del mensaje dentro del formulario*/
    let mensaje = document.getElementById('mensaje-restablecer');//mensaje
    let restablecermodal = M.Modal.getInstance(document.querySelector('#modalrestablecer'));//modal
    //creamos el form para añadir el usuario
    let form = new FormData(document.getElementById('renovarcontr-form'));
    form.append('id', IDCLI.value);
    if (CONTRAN.value.length != 0 || CONTRAC.value.length != 0) {
        contrasenasIguales();
        if (mensaje.style.display != 'block') {
            //Ejecutamos metodo que actualize la contraseña del cliente
            RESTABLECERCTR.classList.add('disabled');
            preloaderC.style.display = 'block';
            fetch(API_CLIENTES + 'actualizarContraCli', {
                method: 'post',
                body: form
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                        if (response.status) {
                            console.log('se ejecuta')
                            preloaderC.style.display = 'none';
                            RESTABLECERCTR.classList.remove('disabled');
                            sweetAlert(1, response.message, null)
                            restablecermodal.close();
                        } else {
                            preloaderC.style.display = 'none';
                            RESTABLECERCTR.classList.remove('disabled');
                            sweetAlert(2, response.exception, null)
                        }
                    });
                } else {
                    RESTABLECERCTR.classList.remove('disabled');
                    console.log(request.status + ' ' + request.statusText);
                }
            });
        } else {
            mensaje.innerText = 'Las contraseñas deben coincidir';
            mensaje.style.display = 'block';
        }
    } else {
        mensaje.style.display = 'block';
        mensaje.innerText = 'No se permiten espacios vacios';
    }
});

//Mostrar-Ocultar restablecer contraseñas
OJO2.addEventListener('click', function () {
    let CONTRAINPUT = document.getElementById('contraseña_nueva');
    let CONTRAC = document.getElementById('contraseña_confirma');
    if (CONTRAINPUT.type == "password") {
        CONTRAINPUT.type = "text";
        CONTRAC.type = "text";
        OJO2.innerText = "visibility_off";
    } else {
        CONTRAINPUT.type = "password";
        CONTRAC.type = "password"
        OJO2.innerText = "visibility";
    }
});

//Función para comprobar si las contraseñas son iguales
function contrasenasIguales() {
    let mensaje = document.getElementById('mensaje-restablecer');//mensaje
    if (CONTRAN.value != CONTRAC.value) {
        mensaje.innerText = 'Las contraseñas no coinciden';
        mensaje.style.display = 'block';
    } else if (CONTRAN.value.length < 6) {
        mensaje.innerText = 'Las contraseñas deben tener más de 6 caracteres';
        mensaje.style.display = 'block';
    }
    else {
        mensaje.style.display = 'none';
    }
}

//Funciónes para comprobar las contraseñas mientras estan siendo escritas
CONTRAN.addEventListener('keyup', function () {
    contrasenasIguales();
});
CONTRAC.addEventListener('keyup', function () {
    contrasenasIguales();
});

//Validaciones para los campos del modal de actualizar cliente
//Validar solo letras en los campos de Nombre y Apellido del cliente
NOMBRECL.addEventListener('keypress', function (e) {
    if (!soloLetras(event, 1)) {
        e.preventDefault();
    }
});

APELLIDOCL.addEventListener('keypress', function (e) {
    if (!soloLetras(event, 1)) {
        e.preventDefault();
    }
});

//Validar guion dui en el campo de dui y solo numeros Y telefono
DUICL.addEventListener('keypress', function (e) {
    if (!soloNumeros(event, 2)) {
        e.preventDefault();
    }
});

TELCL.addEventListener('keypress', function (e) {
    if (!soloNumeros(event, 2)) {
        e.preventDefault();
    }
});

DUICL.addEventListener('keyup', function (e) {
    guionDUI(e, DUICL);
});

TELCL.addEventListener('keyup', function (e) {
    guionTelefono(e, TELCL);
});

//Boton de actualizar cliente
BTNACTUALIZAR.addEventListener('click', function () {
    //Validamos campos vacios creando un arreglo de los componentes
    let arreglo = [NOMBRECL, APELLIDOCL, CORREOCL, DUICL, TELCL, USUARIOCL, DIRECCL];
    let action = '';
    console.log(IDCLI.value);
    if (validarCamposVacios(arreglo) != false) {
        IDCLI.value ? action = 'update' : action = 'create';
        // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
        saveRowL(API_CLIENTES, action, 'actcliente-form', 'modalact', 0);
        BTNACTUALIZAR.classList.add('disabled');
        CAMBIARCTRBTN.classList.add('disabled');
    } else {
        MENSAJEACT.innerText = 'No se aceptan campos vacios';
        MENSAJEACT.style.display = 'block';
    }
});

//Metodo del buscador

const BUSCADORBTN = document.getElementById("buscador-btn");

BUSCADORBTN.addEventListener('click', function () {
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_CLIENTES, 'buscador-form');
});

const BUSCADORINP = document.getElementById("search");

BUSCADORINP.addEventListener('keyup', function (e) {
    if (BUSCADORINP.value == '') {
        readRowsLimit(API_CLIENTES, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    } else {
        // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
        dynamicSearcher2(API_CLIENTES, 'buscador-form');
    }
});

function noDatos() {
    let h = document.createElement("h3");
    let text = document.createTextNode("0 resultados");
    h.appendChild(text);
    CLIENTESCONTENEDOR.innerHTML = "";
    CLIENTESCONTENEDOR.append(h);
}
