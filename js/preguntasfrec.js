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
    M.Collapsible.init(document.querySelectorAll('.collapsible'));
    M.FloatingActionButton.init(document.querySelectorAll('.fixed-action-btn'))
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    /*Obtenemos el texto dentro del input invisible*/
    var content = document.getElementById('copywhat').innerHTML;
    /*Ordenamos al navegador a guadar el texto en el portapapeles*/ 
    navigator.clipboard.writeText(content)
}
/*Ocultar el NavBar si se aprieta en seguir viendo*/
var btncontinuarv = document.getElementById('seguirv_carrito');
btncontinuarv.addEventListener('click', function () {
    let carrito = M.Sidenav.getInstance(document.querySelector('#carrito'));
    carrito.close();
});
/*Cambiar el estado del collap para aplicar estilo*/
function cambiarborde(id) {
    let collaps = document.getElementsByName('collapr');
    for (let i = 0; i < collaps.length; i++) {
        if(collaps[i].classList.contains('desplegado') && collaps[i] != id){
            collaps[i].classList.toggle('desplegado');
        }
    }
    id.classList.toggle('desplegado');
};
/*Boton de ir hacia arrina*/
var hastatop = document.getElementById('hasta_arriba');
window.onscroll = function(){
    if(document.documentElement.scrollTop >100){
        hastatop.style.display = "block";
    }else{
        hastatop.style.display = "none";
    }
};

hastatop.addEventListener('click', function(){
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    })
});
/*Ocultar navbar mobile tras aparecer carrito*/
var btnabrircarrito = document.getElementById('abrircarrito-mobile');
btnabrircarrito.addEventListener('click', function () {
    let navmobile = M.Sidenav.getInstance(document.querySelector('#mobile-demo'));
    navmobile.close();
});