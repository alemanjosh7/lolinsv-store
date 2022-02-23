/*document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Modal.init(document.querySelectorAll('.modal'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.modal');
    let options = {

    };
    M.Modal.init(elems, options);
});

document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems);
});
/*Hasta aqui llega*/

/*Mostrar u ocultar para dui y número en el perfil*/
/*DUI*/
var ojo = document.getElementById('ocultarmostrar_duiuser');
var dui = document.getElementById('dui_usuario');

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
var telefono = document.getElementById('telefono_usuario');

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
var btncancelperfil = document.getElementById('cancelactdatosperfil_boton');
btnactperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "block";
    btnactperfil.classList.add("disabled")
});

btncancelperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "none";
    btnactperfil.classList.remove("disabled")
});

/*Mostrar-Ocultar preloader para la actualización de datos del perfil*/
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
            preloaderactcontra.style.display = "block";
            btnactcontra.classList.add("disabled");
            mensaje.style.display = 'none';
            contra.value = '';
            contrac.value = '';
            contran.value = '';
        } else {
            mensaje.innerText = 'Las contraseñas deben coincidir';
            mensaje.style.display = 'block';
        }
    }else{
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