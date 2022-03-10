var slideIndex = 1;
showSlides(slideIndex);
function plusSlides(n) {
  showSlides((slideIndex += n));
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  if (n > slides.length) {
    slideIndex = 1;
  }
  if (n < 1) {
    slideIndex = slides.length;
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  slides[slideIndex - 1].style.display = "block";

  setTimeout(showSlides, 2000);
}
/*Estilo de las opciones de los carritos y el navbar mobile*/
var opcionesCarrito = {
  edge: "right",
};
var navbarmobile = {
  edge: "left",
};
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
/*Ocultar navbar mobile tras aparecer carrito*/
var btnabrircarrito = document.getElementById("abrircarrito-mobile");
btnabrircarrito.addEventListener("click", function () {
  let navmobile = M.Sidenav.getInstance(document.querySelector("#mobile-demo"));
  navmobile.close();
});
/*Ocultar el NavBar si se aprieta en seguir viendo*/
var btncontinuarv = document.getElementById("seguirv_carrito");
btncontinuarv.addEventListener("click", function () {
  let carrito = M.Sidenav.getInstance(document.querySelector("#carrito"));
  carrito.close();
});
document.addEventListener("DOMContentLoaded", function () {
  var instance = M.Carousel.init({
    fullWidth: true,
  });
  var instances = M.Carousel.init(document.querySelectorAll(".carousel"));
});

/*Boton de ir hacia arrina*/

var hastatop = document.getElementById("hasta_arriba");

window.onscroll = function () {
  if (document.documentElement.scrollTop > 100) {
    hastatop.style.display = "block";
  } else {
    hastatop.style.display = "none";
  }
};

hastatop.addEventListener("click", function () {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});
