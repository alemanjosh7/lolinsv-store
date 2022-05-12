// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'dashboard/admins.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
const API_USUARIOS = SERVER + 'dashboard/usuarios.php?action=';


document.addEventListener('DOMContentLoaded', function () {
    comprobarAdmins();//Comprobamos si hay admins
    
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    BOTONATRAS.style.display = 'none';
    readRowsLimit(API_USUARIOS,0);
    predecirAdelante();//
     // Se define una variable para establecer las opciones del componente Modal.
     let options = {
        dismissible: false,
        onOpenStart: function () {
            // Se restauran los elementos del formulario.
            document.getElementById('save-form2').reset();
        }
    }
   
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Modal.init(document.querySelectorAll('.modal'),options);
});


//Declarando algunos componentes
const HASTATOP = document.getElementById('hasta_arriba');//Boton de hasta arriba
const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras
const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai
const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf
const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante
const MODALACT = document.getElementById("actualizar-usuario");//Modal de actualización de cliente
const MODALREC = document.getElementById("modalrestablecer");//Modal de cambiar contraseña
const CAMBIARCTRBTN = document.getElementById("cambiarcontra");//Cambiar Contraseña
const RESTABLECERCTR = document.getElementById('restablecerContraseña');//boton de restablecer contraseña
const OJO2 = document.getElementById('ocultarmostrar_contraseñas');
const CONTRAN = document.getElementById('contraseña_nueva');//input de la contraseña nueva en restablecer contraseña
const CONTRAC = document.getElementById('contraseña_confirma');//input de la confirmación de la contraseña en restablecer contraseña
const IDADM = document.getElementById('id_admin');


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
    predictLImit(API_USUARIOS, limit);
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
        readRowsLimit(API_USUARIOS
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
        readRowsLimit(API_USUARIOS
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
        <div class="col l3 s12 m6">
                    <div class="card" id="tarjetas-privado" id="tarjetas-privado">
                            <div class="botones">
                            <!--Boton borrar-->
                                <a onclick="openDelete(${row.id_admin})" class="btn-floating waves-effect waves-light red eliminarbtn"><i
                                    class="material-icons tooltipped" data-position="left" data-tooltip="Eliminar CLiente">delete</i></a>
                                <!--Boton editar-->
                                <a onclick="openUpdate(${row.id_admin})" class="btn-floating waves-effect waves-grey lighten-1 modbtn"><i
                                    class="material-icons tooltipped" data-position="right" data-tooltip="Modificar CLiente">edit</i></a>
                            </div>
                        <div class="imagen-usuario center-align col s12 m12 s12">
                            <img src="../../resources/img/icons/UserIcon-Perfil.png">
                            <span class="card-title">
                                <h6>${row.nombre_admin}</h6>
                        </div>
                        <div class="card-content">
                            <p>${row.apellido_admin}</p>
                        </div>
                    </div>
                </div>          
        `;
    });

    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('Admins').innerHTML = content;
    // Se inicializa el componente Material Box para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

//Función para eliminar una categorias
function openDelete(id_admin) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_admin', id_admin);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js y paso el valor de 8 para recargar los clientes
    confirmDeleteL(API_USUARIOS, data, 0);
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


// Función para preparar el formulario al momento de insertar un registro.
function openCreate() {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('actualizar-usuario')).open();
    // Se asigna el título para la caja de diálogo (modal).
    // Se establece el campo de archivo como obligatorio.
    document.getElementById('contrasena').classList.remove('hide'); 
    document.getElementById('coContrasena').classList.remove('hide');
    CAMBIARCTRBTN.classList.add('hide');


}


// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id_admin) {
    CAMBIARCTRBTN.classList.remove('hide');
    document.getElementById('contrasena').classList.add('hide');
    document.getElementById('coContrasena').classList.add('hide');
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('actualizar-usuario')).open();
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_admin', id_admin);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_USUARIOS + 'readOne', {
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
                    document.getElementById('id_admin').value = response.dataset.id_admin;
                    document.getElementById('nombre_admin').value = response.dataset.nombre_admin;
                    document.getElementById('apellidoA').value = response.dataset.apellido_admin;
                    document.getElementById('usuarioA').value = response.dataset.usuario;
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
    form.append('id', IDADM.value);
    if (CONTRAN.value.length != 0 || CONTRAC.value.length != 0) {
        contrasenasIguales();
        if (mensaje.style.display != 'block') {
            //Ejecutamos metodo que actualize la contraseña del cliente
            RESTABLECERCTR.classList.add('disabled');
            fetch(API_USUARIOS + 'actContraUsuario', {
                method: 'post',
                body: form
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                        if (response.status) {
                            console.log('se ejecuta')                        
                            RESTABLECERCTR.classList.remove('disabled');
                            sweetAlert(1, response.message, null)
                            restablecermodal.close();
                        } else {
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



// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('save-form2').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
    (document.getElementById('id_admin').value) ? action = 'update' : action = 'create';
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRowL(API_USUARIOS, action, 'save-form2', 'actualizar-usuario', 0);
});


BUSCADOR.addEventListener('keyup',function(e){
    if(BUSCADOR.value == ''){
        readRowsLimit(API_USUARIOS, 0);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
    }else{
        // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    dynamicSearcher2(API_USUARIOS, 'search-form');
    }
});

function noDatos(){
    let h = document.createElement("h3");
    let text = document.createTextNode("0 resultados");
    h.appendChild(text);
    document.getElementById('Admins').innerHTML = '';
    document.getElementById('Admins').append(h);
}


