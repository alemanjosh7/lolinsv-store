// Constante para establecer la ruta y parámetros de comunicación con la API_HEADER_GLBVAR.
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
const API_CLIENTES = SERVER + 'public/clientes.php?action=';
const API_USUARIOS = SERVER + 'public/clientes.php?action=';
const API_CLIENTESENDPOINT = SERVER + 'public/login.php?action=';

var opcionesModalg = {
    //onOpenStart: function () {
    // Se restauran los elementos del formulario.
    dissmisible: false,
    onOpenStart: function () {
        // Se restauran los elementos del formulario.
        document.getElementById('cambiocontra').reset();
    }
}



document.addEventListener('DOMContentLoaded', function () {
    comprobaClientes()
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.Modal.init(document.querySelectorAll('.modal'), opcionesModalg);

    fetch(API_CLIENTES + 'readProfile', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del usuario que ha iniciado sesión.
                    document.getElementById('nombre_usuario-perfil').value = response.dataset[0].nombre_cliente;
                    document.getElementById('apellido_usuario-perfil').value = response.dataset[0].apellido_cliente;
                    document.getElementById('e-mail').value = response.dataset[0].correo_cliente;
                    document.getElementById('dui_usuario-perfil').value = response.dataset[0].dui_cliente;
                    document.getElementById('telefono_usuario-perfil').value = response.dataset[0].telefono_cliente;
                    document.getElementById('direccion_usuario-perfil').value = response.dataset[0].direccion_cliente;
                    document.getElementById('Username').value = response.dataset[0].usuario;
                    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
                    M.updateTextFields();
                    M.textareaAutoResize(document.getElementById('direccion_usuario-perfil'));
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
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





document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems);
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
/*Mostrar u ocultar para dui y número en el perfil*/
/*DUI*/
var ojo = document.getElementById('ocultarmostrar_duiuser');
var dui = document.getElementById('dui_usuario-perfil');

ojo.addEventListener("click", function () {
    if (dui.type == "password") {
        dui.type = "text"
        ojo.innerText = "visibility_off"
    } else {
        dui.type = "password"
        ojo.innerText = "visibility"
    }
});

/*Telefono*/
var ojo2 = document.getElementById('ocultarmostrar_teleuser');
var telefono = document.getElementById('telefono_usuario-perfil');

ojo2.addEventListener("click", function () {
    if (telefono.type == "password") {
        telefono.type = "text"
        ojo2.innerText = "visibility_off"
    } else {
        telefono.type = "password"
        ojo2.innerText = "visibility"
    }
});

/*Mostrar-Ocultar preloader para la actualización de datos del perfil*/
var btnactperfil = document.getElementById('aceptaractdatosperfil_boton');
var preloaderactperfil = document.getElementById('actdatosperfil_preloader');
var btncancelperfil = document.getElementById('cancelactdatosperfil_boton');//
btnactperfil.addEventListener("click", function () {
    //Validamos campos vacios creando un arreglo de los componentes
    let arreglo = [
        document.getElementById('nombre_usuario-perfil'),
        document.getElementById('apellido_usuario-perfil'),
        document.getElementById('e-mail'),
        document.getElementById('dui_usuario-perfil'),
        document.getElementById('telefono_usuario-perfil'),
        document.getElementById('direccion_usuario-perfil'),
        document.getElementById('Username')];
    //Validamos 
    if (validarCamposVacios(arreglo) != false) {
        let form = new FormData(document.getElementById('datosForm'));
        form.append('Username', document.getElementById('Username').value);

        fetch(API_CLIENTES + 'update', {
            method: 'post',
            body: form
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                // Se obtiene la respuesta en formato JSON.
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        // Se muestra un mensaje de éxito.
                        sweetAlert(1, response.message, 'perfil.html');
                    } else {
                        sweetAlert(2, response.exception, null);
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        })
    } else {
        sweetAlert(3, 'No se permiten campos vacios', null);
    }
    preloaderactperfil.style.display = "block";
    btnactperfil.classList.add("disabled")
});

btncancelperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "none";
    btnactperfil.classList.remove("disabled")
});

/*Mostrar-Ocultar preloader para la actualización de contraseña del perfil*/
var btnactcontra = document.getElementById('aceptaractcontra_boton');
var preloaderactcontra = document.getElementById('actdatoscontra_preloader');
var btncancelcontra = document.getElementById('cancelactdatoscontra_boton');
/* Actualizar contraseña */
btnactcontra.addEventListener("click", function () {
    let contra = document.getElementById('contraseña_actual');
    let contran = document.getElementById('contraseña_nueva');
    let contrac = document.getElementById('contraseña_confirma');
    let mensaje = document.getElementById('mensajecontra');
    if (contra.value.length != 0 && contran.value.length != 0 && contrac.value.length != 0) {
        if (contran.value == contrac.value) {
            fetch(API_USUARIOS + 'changePassword', {
                method: 'post',
                body: new FormData(document.getElementById('cambiocontra'))
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    // Se obtiene la respuesta en formato JSON.
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            preloaderactcontra.style.display = "block";
                            btnactcontra.classList.add("disabled");
                            mensaje.style.display = 'none';
                            contra.value = '';
                            contrac.value = '';
                            contran.value = '';
                            // Se muestra un mensaje de éxito.
                            sweetAlert(1, response.message, 'perfil.html');
                        } else {
                            preloaderactcontra.style.display = "none";
                            btnactcontra.classList.remove("disabled");
                            mensaje.style.display = 'none';
                            contra.value = '';
                            contrac.value = '';
                            contran.value = '';
                            // 
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
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

btncancelcontra.addEventListener("click", function () {
    let mensaje = document.getElementById('mensajecontra');
    preloaderactcontra.style.display = "none";
    btnactcontra.classList.remove("disabled");
    mensaje.style.display = 'none';
});
/* Mostrar/Ocultar contraseñas para la actualización*/
let ojo3 = document.getElementById('ocultarmostrar_contraseñas');
ojo3.addEventListener("click", function () {
    let contraseña = document.getElementById('contraseña_actual');
    let contraseñan = document.getElementById('contraseña_nueva');
    let contraseñac = document.getElementById('contraseña_confirma');
    if (contraseña.type == "password") {
        contraseña.type = "text"
        contraseñan.type = "text"
        contraseñac.type = "text"
        ojo3.innerText = "visibility_off"
    } else {
        contraseña.type = "password"
        contraseñan.type = "password"
        contraseñac.type = "password"
        ojo3.innerText = "visibility"
    }
});

//Metodo para cerrar session
document.getElementById('cerrarsesion-boton').addEventListener('click',function(){
    Swal.fire({
        title: 'Advertencia',
        text: '¿Desea cerrar sesion?',
        icon: 'warning',
        showDenyButton: true,
        confirmButtonText: 'Si',
        denyButtonText: 'Cancelar',
        allowEscapeKey: false,
        allowOutsideClick: false,
        background: '#F7F0E9',
        confirmButtonColor: 'green',
    }).then(function (value) {
        // Se comprueba si fue cliqueado el botón Sí para hacer la petición de borrado, de lo contrario no se hace nada.
        if (value.isConfirmed) {
            fetch(API_CLIENTES + 'logOut', {
                method: 'get'
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
                if (request.ok) {
                    // Se obtiene la respuesta en formato JSON.
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            sweetAlert(1, response.message, 'index.html');
                        } else {
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    console.log(request.status + ' ' + request.statusText);
                }
            });
        }
    });
});