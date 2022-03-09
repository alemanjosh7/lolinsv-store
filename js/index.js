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
document.addEventListener("DOMContentLoaded", function () {
  var instance = M.Carousel.init({
    fullWidth: true,
  });
  var instances = M.Carousel.init(document.querySelectorAll(".carousel"));
});

// Or with jQuery

$(document).ready(function () {
  $(".carousel").carousel();
});
