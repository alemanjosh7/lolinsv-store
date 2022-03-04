/*Estilo de las opciones de los carritos y el navbar mobile*/
var opcionesCarrito = {
    edge: 'right'
}
var navbarmobile = {
    edge: 'left'
}
//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Sidenav.init(document.querySelectorAll('#mobile-demo'), navbarmobile);
    M.Sidenav.init(document.querySelectorAll('#carrito'), opcionesCarrito);
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    M.Modal.init(document.querySelectorAll('.modal'));
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}
/*Programación del select de paises*/
const select = document.querySelector('#select');
const opcionesselect = document.querySelector('#opciones-select');
const contenidoselect = document.querySelector('#select .contenido-select');

document.querySelectorAll('#opciones-select > .opcion-select').forEach((opcion) => {
    opcion.addEventListener('click',(e) => {
        e.preventDefault();
        contenidoselect.innerHTML = e.currentTarget.innerHTML;
        opcionesselect.classList.toggle('active');
        select.classList.toggle('active');
    });
});

select.addEventListener('click',function(){
    select.classList.toggle('active');
    opcionesselect.classList.toggle('active');
});

/*Mostrar-Ocultar preloader para la actualización de datos del perfil*/
var btnactperfil = document.getElementById('aceptarcompra_boton');
var preloaderactperfil = document.getElementById('confirmarcompra_preloader');
var btncancelperfil = document.getElementById('cancelacompra_boton');
btnactperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "block";
    btnactperfil.classList.add("disabled")
});

btncancelperfil.addEventListener("click", function () {
    preloaderactperfil.style.display = "none";
    btnactperfil.classList.remove("disabled")
});
/*Ocultar navbar mobile tras aparecer carrito*/
var btnabrircarrito = document.getElementById('abrircarrito-mobile');
btnabrircarrito.addEventListener('click', function () {
    let navmobile = M.Sidenav.getInstance(document.querySelector('#mobile-demo'));
    navmobile.close();
});
/*Ocultar el NavBar si se aprieta en seguir viendo*/
var btncontinuarv = document.getElementById('seguirv_carrito');
btncontinuarv.addEventListener('click', function () {
    let carrito = M.Sidenav.getInstance(document.querySelector('#carrito'));
    carrito.close();
});

/*Opciones xtra
.opciones-select{
    margin-top: 15px;
    border-radius: 5px;
    background-color: white;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, .16);
    max-height: 200px;
    overflow: auto;
    z-index: 100;
    width: 50%;
    display: none;
    position: absolute;
    left: calc(25%);
}
.opciones-select.active{
    display: block;
}
.contenido-opcion{
    width: 100%;
    display: flex;
    align-items: center;
    border-bottom: 2px solid black;
    transition: 2s ease all;
    padding: 5px;
}
.contenido-opcion img{
    position: absolute;
    left: 10%;
}
.contenido-opcion p{
    margin-left: 65px;
}

*/ 


/*OPCIONES EXTRA*/
/*
.opciones-select{
    margin-top: 15px;
    border-radius: 5px;
    background-color: white;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, .16);
    max-height: 200px;
    overflow: auto;
    z-index: 100;
    width: 100%;
    display: none;
}
/*Contenedor de las opciones cuando esten activas
.opciones-select.active{
    display: block;
}
/*Contenedor de las opciones
.contenido-opcion{
    width: 100%;
    display: flex;
    align-items: center;
    border-bottom: 2px solid black;
    transition: .2s ease all;
    padding: 5px;
}
*/