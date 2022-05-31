// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_COMENTARIOS = SERVER + 'public/comentariosClientes.php?action=';
const API_PRODUCTOS = SERVER + 'public/productos.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';

//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    M.Modal.init(document.querySelectorAll('.modal'));
    //Iniciamos algunos metodos
    obtenerIdProducto();//Comprobamos y obtenemos el id del producto seleccionado
    llenarComentarios(limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
});

/*Boton de ir hacia arrina*/
var hastatop = document.getElementById('hasta_arriba');
window.onscroll = function () {
    if (document.documentElement.scrollTop > 100) {
        hastatop.style.display = "block";
    } else {
        hastatop.style.display = "none";
    }
};

hastatop.addEventListener('click', function () {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    })
});

//Inicializamos algunos componentes
const NUMCOMENTARIOS = document.getElementById('cantidad_coment');//Cantidad de comentarios
const COMENTARIOSCONT = document.getElementById('comentarios_cont');//Contenedor de comentarios
const CARGARMASCOMENTARIOSBTN = document.getElementById('cancelar_restablecer');//Boton de cargar más comentarios
const ANADIRCOMENT = document.getElementById('anadir-coment');//boton de añadir comentario
const ANADIRCOMENTMODAL = document.getElementById('modalrestablecer');//Modal de añadir un comentario
const CALIFICACIONES = document.getElementsByClassName('calificacion_cont');//Componentes de la calificacion
const COMENTARIOINPUT = document.getElementById('comentario_input');//Input del comentario
const PRELOADERCOMENT = document.querySelectorAll('.comentario_preloader')[0];//Preloader del formulario de comentario
const MENSAJECOMENT = document.querySelectorAll('.mensaje_comentario')[0];//Mensaje del comentario
const CONTENEDORINFPRD = document.getElementById('infoprd_contenedor');//Contenedor de la información del producto
//Inicializamos algunas variables
var limit = 5;
var calificacion = 0;

/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
const imgs = document.querySelectorAll('.img-select a');
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
    imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
    });
});

function slideImage() {
    const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

    document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
}

window.addEventListener('resize', slideImage);
//Un ejemplo para como se obtiene el id
function obtenerIdProducto() {
    fetch(API_GLBVAR + 'getIdProducto', {
        method: 'get',
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                //Se comprueba si se logro setear el id
                if (response.status) {
                    console.log(response.id_producto);
                    //Se ejecuta el metodo que carga la información del producto;
                    cargarInfoProducto(response.id_producto);
                } else {
                    //Como no se ha iniciado ningun
                    location.href = 'productos.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
    //Podemos hacer que esto setee una variable o retorne la variable de response.id_producto para trabaja con él de igual forma harías
    //similar con el id del pedido, creandolo y seteandolo en caso no este, o trayendolo en caso este
}

//Función para llenar la sección de comentarios
//Función para llenar el contenedor de clientes con los datos obtenidos del controlador de components
function llenarComentarios(limit) {
    let content = '';
    let form = new FormData();
    form.append('limit', limit);
    fetch(API_COMENTARIOS + 'readAllLimit', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria para obtener los datos, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    //Se ejecuta el metodo de llenado
                    CARGARMASCOMENTARIOSBTN.classList.remove('hide');
                    //Llenamos el contenedor con los comentarios
                    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                        content += `
                            <div class="container primera">
                                <div class="user">
                                    <div class="photo-user ">
                                        <img src="../../resources/img/icons/user.png" alt="">
                                    </div>
                                <div class="name-user">${row.usuario}</div>
                                <div class="product-rating user-rating">
                                    <img class="responsive-img" src="../../api/images/calificaciones/${row.fk_id_valoraciones}.png" height="50px" width="50px">
                                    <span class="rating">${row.fk_id_valoraciones}/5 </span> 
                                </div>
                            </div>
                            <div class="line-2"></div>
                                <div class="comment">
                                    <p>${row.comentario}.</p>
                                </div>
                            </div>
                            `;
                    });
                    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
                    COMENTARIOSCONT.innerHTML = content;
                } else {
                    let h = document.createElement("h5");
                    let text = document.createTextNode("Aun no hay comentarios para este producto, si ya lo compraste. ¡Se el primero!");
                    h.appendChild(text);
                    COMENTARIOSCONT.innerHTML = '';
                    COMENTARIOSCONT.append(h);
                    CARGARMASCOMENTARIOSBTN.classList.add('hide');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Cargar más comentarios al apretar el boton
CARGARMASCOMENTARIOSBTN.addEventListener('click', function () {
    limit = limit + 5;
    llenarComentarios(limit);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
});

//Metodo para añadir un comentario
ANADIRCOMENT.addEventListener('click', function () {
    //Validamos si hay una sesión
    fetch(API_COMENTARIOS + 'comprobarComentario', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {
                    if (!response.status) {
                        //Reiniciamos el formulario
                        document.getElementById('comentario_input').value = '';
                        M.textareaAutoResize(document.getElementById('comentario_input'));
                        let caltext = document.getElementsByClassName('texto-cal');
                        for (let index = 0; index < caltext.length; index++) {
                            caltext[index].classList.remove('green-text');
                        }
                        calificacion = 0;
                        MENSAJECOMENT.style.display = 'none';
                        PRELOADERCOMENT.style.display = 'none';
                        //Abrimos el modal
                        M.Modal.getInstance(ANADIRCOMENTMODAL).open();
                    } else {
                        sweetAlert(4, response.exception, null);
                    }
                }
                else {
                    sweetAlert(4, 'Debe iniciar sesión para poder realizar un comentario', 'login.html');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});

/*Metodo para seleccionar y setear la calificación una vez se seleccione*/
document.querySelectorAll(".calificacion_cont").forEach(el => {
    el.addEventListener("click", e => {
        calificacion = e.target.getAttribute("id");
        console.log(calificacion);
        let caltext = document.getElementsByClassName('texto-cal');
        for (let index = 0; index < caltext.length; index++) {
            let id = caltext[index].getAttribute("id");
            if (id == calificacion) {
                caltext[index].classList.add('green-text');
            } else {
                caltext[index].classList.remove('green-text');
            }
        }
    });
});

//Evento para enviar el formulario del comentario
document.querySelectorAll('.boton_comentario')[0].addEventListener('click', function () {
    //Validamos que se halla selecionado una calificación y se halla ingresado un comentario
    if (calificacion != 0 && COMENTARIOINPUT.value.length > 0) {
        MENSAJECOMENT.style.display = 'none';
        PRELOADERCOMENT.style.display = 'block';
        let form = new FormData();
        form.append('valoracion', calificacion);
        form.append('comentario', COMENTARIOINPUT.value);
        fetch(API_COMENTARIOS + 'crearComentario', {
            method: 'post',
            body: form
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                request.json().then(function (response) {
                    // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                    if (response.status) {
                        sweetAlert(1, response.message, null);
                        //Cerramos el modal
                        M.Modal.getInstance(ANADIRCOMENTMODAL).close();
                        llenarComentarios(limit);
                    } else {
                        sweetAlert(2, response.exception, null);
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        });
    } else {
        MENSAJECOMENT.style.display = 'block';
        MENSAJECOMENT.innerText = 'Debe seleccionar una calificación e introducir un comentario antes de subir el comentario';
    }
});

//Metodo que carga la información del producto
function cargarInfoProducto(id) {
    //Se crea un formulario y se setea el id del producto
    let form = new FormData();
    form.append('id', id);
    let content;
    fetch(API_PRODUCTOS + 'obtenerInfoProducto', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    //Como se obtuvo la información con exito, se cargaran los campos
                    // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                    content = `
                        <!-- card left -->
                        <div class="product-imgs" id="card-product">
                            <div class="img-display">
                                <div class="img-showcase">
                                    <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                    <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                    <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                    <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                </div>
                            </div>
                            <div class="img-select">
                                <div class="img-item">
                                    <a data-id="1">
                                        <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                    </a>
                                </div>
                                <div class="img-item">
                                    <a data-id="2">
                                        <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                    </a>
                                </div>
                                <div class="img-item">
                                    <a data-id="3">
                                        <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                    </a>
                                </div>
                                <div class="img-item">
                                    <a data-id="4">
                                        <img src="../../api/images/productos/${response.dataset.imagen_producto}" alt="shoe image">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- card right -->
                        <div class="product-content">
                            <h2 class="product-title">${response.dataset.nombre_producto}</h2>
                            <div class="product-rating">
                                <img src="../../api/images/calificaciones/${response.dataset.fk_id_valoraciones}.png" alt="">
                                <span class="rating">${response.dataset.fk_id_valoraciones} de calificación </span>
                                <span class="opinions">
                                    < </span>
                                        <span id='calif_totales'>2 calificaciones</span>
                            </div>
        
                            <div class="product-price">
                                <p class="new-price">Precio: <span>$${response.dataset.precio_producto}</span></p>
                            </div>
                            <div class="line-3"></div>
                            <div class="product-detail">
                                <p class="desc">Descripción:</p>
                                <p>${response.dataset.descripcion}.</p>
                                <ul>
                                    <li>Disponible: <span>En stock</span></li>
                                    <li>Categoría: <span>${response.dataset.nombre_categoria}</span></li>
                                    <li>Zona de envío <span>El Salvador</span></li>
                                    <li>Cargo de envío: <span>$5.00</span></li>
                                </ul>
                            </div>
        
                            <div class="purchase-info">
                                <a class="waves-effect waves-light btn" onclick="anadirCarrito()">
                                    <div class="btn-carrito">
                                        <img src="../../resources/img/amigurumis/shopping.png">
                                        <p>Añadir al carrito</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                            `;
                    CONTENEDORINFPRD.innerHTML = content;
                    cargarCalificacionesT(id);
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Metodo para cargar las calificaciones
function cargarCalificacionesT(id){
    //Se crea un formulario y se setea el id del producto
    let form = new FormData();
    form.append('id', id);
    fetch(API_PRODUCTOS + 'valoracionesTotales', {
        method: 'post',
        body: form
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    document.getElementById('calif_totales').innerText = response.dataset.count+' calificaciones';
                    NUMCOMENTARIOS.innerText = 'Comentarios: ' + response.dataset.count;
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

/*Función para añadir al carrito el producto, debes setear el id_pedidoEsta para que te cargue el carrito
si no esta seteado creas un pedido y luego insertas en el detalle de ese pedido si lo esta pues solo insertas podes usar el 
isset() que verifica si una variable esta iniciada*/
function anadirCarrito(){
    console.log('pueba')
};