//Inicializando componentes de Materialize
document.addEventListener("DOMContentLoaded", function () {
  M.Sidenav.init(document.querySelectorAll(".sidenav"));
  M.Sidenav.init(document.querySelectorAll("#mobile-demo"), navbarmobile);
  M.Sidenav.init(document.querySelectorAll("#carrito"), opcionesCarrito);
  M.Slider.init(document.querySelectorAll(".slider"));
  M.Carousel.init(document.querySelectorAll(".carousel"));
  M.Tooltip.init(document.querySelectorAll(".tooltipped"));
  M.FormSelect.init(document.querySelectorAll("select"));
  M.Modal.init(document.querySelectorAll(".modal"));
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
  var content = document.getElementById("copywhat").innerHTML;
  navigator.clipboard.writeText(content);
}