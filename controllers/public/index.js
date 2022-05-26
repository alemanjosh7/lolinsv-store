// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_GLBVAR = SERVER + 'variablesgb.php?action=';

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
//Inicializando componentes de Materialize
document.addEventListener("DOMContentLoaded", function () {
  saludo();
  M.Sidenav.init(document.querySelectorAll(".sidenav"));
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
  if (document.documentElement.scrollTop > 200) {
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


//Creando función para el saludo
function saludo() {
  // Se define un objeto con la fecha y hora actual.
  let today = new Date();
  // Se define una variable con el número de horas transcurridas en el día.
  let hour = today.getHours();
  // Se define una variable para guardar un saludo.
  let greeting = '';
  // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
  if (hour < 12) {
      greeting = 'mañana';
  } else if (hour < 19) {
      greeting = 'tarde';
  } else if (hour <= 23) {
      greeting = 'noche';
  }
  fetch(API_GLBVAR + 'verificarSaludoI', {
      method: 'get',
  }).then(function (request) {
      // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
      if (request.ok) {
          request.json().then(function (response) {
              // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
              if (response.session) {
              } else if (response.status) {
                  sweetAlert(4, "Bienvenido " + response.nombre + " " + response.apellido + " ¡Ten una " + greeting + " productiva!", null);
              } else {
              }
          });
      } else {
          console.log(request.status + ' ' + request.statusText);
      }
  });
}