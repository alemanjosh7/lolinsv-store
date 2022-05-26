// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_PRODUCTOS = SERVER + 'public/productos.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
const ENDPOINT_CATEGORIAS = SERVER + 'public/categorias.php?action=readAll';
/*Estilo de las opciones de los carritos y el navbar mobile*/

//Inicializando componentes de Materialize
document.addEventListener("DOMContentLoaded", function () {
  M.Sidenav.init(document.querySelectorAll(".sidenav"));
  M.Slider.init(document.querySelectorAll(".slider"));
  M.Carousel.init(document.querySelectorAll(".carousel"));
  M.Tooltip.init(document.querySelectorAll(".tooltipped"));
  M.FormSelect.init(document.querySelectorAll("select"));
  M.Modal.init(document.querySelectorAll(".modal"));
  BOTONATRAS.style.display = 'none';
  //Metodos que necistan ser inicializados al cargar
  top3Array[0] = 0;
  fillSelect2(ENDPOINT_CATEGORIAS, 'categoria');//Llenar el select
  top3();//Llenar la fila del top 3
  readRowsLimit(API_PRODUCTOS, top3Array);//Llenar el resto de productos
  predecirAdelante();//Predecir adelante
});

/*Definimos algunos componentes*/
const BUSCADORINP = document.getElementById("search");//Input del buscador
const SELECTCAT = document.getElementById("categoria");//Select de la categoria
const CONTTOP3 = document.getElementById("top3");//Contenedor del top 3
const CONTPRD = document.getElementById("cont-pro");//Contenedor de productos
const BOTONATRAS = document.getElementById("pagnavg-atr");//Boton de navegacion de atras
const BOTONNUMEROPAGI = document.getElementById("pagnumeroi");//Boton de navegacion paginai
const BOTONNUMEROPAGF = document.getElementById("pagnumerof");//Boton de navegacion paginaf
const BOTONADELANTE = document.getElementById("pagnavg-adl");//Boton de navegacion de adelante

//Definimos algunas variables
var top3Array = [0, 'false'];

/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
  var content = document.getElementById("copywhat").innerHTML;
  navigator.clipboard.writeText(content);
}
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

/*Metodo para el buscador*/
BUSCADORINP.addEventListener('keyup', function (e) {
  //Validamos la categoria escogida
  let categoria;
  if (SELECTCAT.options[SELECTCAT.selectedIndex].text == 'Todas las categorias' || SELECTCAT.options[SELECTCAT.selectedIndex].text == 'No hay opciones disponibles') {
    categoria = false;
  } else {
    categoria = SELECTCAT.value;
  }
  //Validamos si hay algo escrito en el buscador
  if (BUSCADORINP.value == '' && categoria == false) {
    readRowsLimit(API_PRODUCTOS, top3Array);
  } else if (BUSCADORINP.value != '' && categoria != false) {
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    top3Array[1] = categoria;
    let formbuscat = new FormData();
    formbuscat.append('limit', 0);
    formbuscat.append('search', BUSCADORINP.value);
    formbuscat.append('cat', categoria);
    dynamicSearcher2Filter(API_PRODUCTOS, formbuscat);
  } else if (BUSCADORINP.value != '') {
    dynamicSearcher2(API_PRODUCTOS, 'search-form');
  }
  else if (BUSCADORINP.value == '' && categoria != false) {
    readRowsLimit(API_PRODUCTOS, top3Array);
  }
  BOTONNUMEROPAGI.innerHTML = 1;
  BOTONNUMEROPAGF.innerHTML = 2;
});

/*Metodo cargar los productos al cambiar el select*/
SELECTCAT.addEventListener('change', (event) => {
  //Validamos la categoria escogida
  let categoria;
  if (SELECTCAT.options[SELECTCAT.selectedIndex].text == 'Todas las categorias' || SELECTCAT.options[SELECTCAT.selectedIndex].text == 'No hay opciones disponibles') {
    categoria = false;
  } else {
    categoria = SELECTCAT.value;
  }
  //Validamos si hay algo escrito en el buscador
  if (BUSCADORINP.value == '' && categoria == false) {
    readRowsLimit(API_PRODUCTOS, top3Array);
  } else if (BUSCADORINP.value != '' && categoria != false) {
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    top3Array[1] = categoria;
    let formbuscat = new FormData();
    formbuscat.append('limit', 0);
    formbuscat.append('search', BUSCADORINP.value);
    formbuscat.append('cat', categoria);
    dynamicSearcher2Filter(API_PRODUCTOS, formbuscat);
  } else if (BUSCADORINP.value != '') {
    dynamicSearcher2(API_PRODUCTOS, 'search-form');
  }
  else if (BUSCADORINP.value == '' && categoria != false) {
    readRowsLimit(API_PRODUCTOS, top3Array);
  }
  BOTONNUMEROPAGI.innerHTML = 1;
  BOTONNUMEROPAGF.innerHTML = 2;
});

//Metodo para cargar el top 3
function top3() {
  fetch(API_PRODUCTOS + 'readTOP3', {
    method: 'get',
  }).then(function (request) {
    // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
    if (request.ok) {
      request.json().then(function (response) {
        // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
        if (response.status) {
          //Como logro traer datos, al ser tres los recorremos mediante el ciclo for
          //Iniciamos variable de contenido
          let content = '';
          for (let index = 0; index < response.dataset.length; index++) {
            //Añadimos el id al arreglo
            top3Array[index + 2] = Number(response.dataset[index].fk_id_producto);
            content += `
              <div class="col s4">
                <a onclick = "infoProducto(${response.dataset[index].fk_id_producto})">
                  <div class="card info-boton">
                      <div class="card-image valign-wrapper">
                          <img src="${SERVER}images/productos/${response.dataset[index].imagen_producto}">
                      </div>
                      <div class="card-content center-align">
                          <span class="card-title">${response.dataset[index].nombre_producto}</span>
                          <p>$${response.dataset[index].precio_producto}</p>
                      </div>
                  </div>
                </a>
              </div>
            `;
          }
          //Se agregan las filas al contenedor
          CONTTOP3.innerHTML = content;
          console.table(top3Array)
        } else {
          console.log('No hay');
          //Como no hay un carrito, entonces colocaremos un texto informando 
          let h = document.createElement("h5");
          let text = document.createTextNode("Lo sentimos, aun no hay productos vendido, lo sentimos");
          h.appendChild(text);
          CONTTOP3.innerHTML = '';
          CONTTOP3.append(h);
        }
      });
    } else {
      console.log(request.status + ' ' + request.statusText);
    }
  });
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
  let content = '';
  // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
  dataset.map(function (row) {
    // Se crean y concatenan las filas de la tabla con los datos de cada registro.
    content += `
      <div class="col s4">
        <a onclick = "infoProducto(${row.id_producto})">
          <div class="card info-boton">
              <div class="card-image valign-wrapper">
                  <img src="${SERVER}images/productos/${row.imagen_producto}">
              </div>
              <div class="card-content center-align">
                  <span class="card-title">${row.nombre_producto}</span>
                  <p>$${row.precio_producto}</p>
              </div>
          </div>
        </a>
      </div>
    `;
  });
  // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
  CONTPRD.innerHTML = content;
}
function noDatos() {
  let h = document.createElement("h3");
  let text = document.createTextNode("0 resultados");
  h.appendChild(text);
  CONTPRD.innerHTML = '';
  CONTPRD.append(h);
}

//Funciones para la páginación

//Función para saber si hay otra página
function predecirAdelante() {
  //Colocamos el boton con un display block para futuras operaciones
  BOTONADELANTE.style.display = 'block';
  //Obtenemos el número de página que seguiría al actual
  let paginaFinal = (Number(BOTONNUMEROPAGF.innerHTML)) + 2;
  console.log("pagina maxima " + paginaFinal);
  //Calculamos el limite que tendria el filtro de la consulta dependiendo de la cantidad de Clientes a mostrar
  let limit = (paginaFinal * 9) - 9;
  console.log("El limite sería: " + limit);
  top3Array[0] = limit;
  console.table(top3Array);
  //Ejecutamos el metodo de la API para saber si hay productos y esta ejecutará una función que oculte o muestre el boton de adelante
  predictLImit2(API_PRODUCTOS, limit);
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
    let limit = (number * 8) - 8;
    //Se ejecuta la recarga de datos enviando la variable de topAct
    //Ejecutamos la función para predecir si habrá un boton de adelante
    top3Array[0] = limit;
    readRowsLimit(API_PRODUCTOS, top3Array);//Enviamos el metodo a buscar los datos y como limite 0 por ser el inicio
  });
});
//Función para setear el id del producto y enviarlo a la información del producto
function infoProducto(id) {
  console.log(id)
  let form = new FormData();
  form.append('id_producto',id);
  fetch(API_GLBVAR + 'setIdProducto', {
    method: 'post',
    body: form
  }).then(function (request) {
    // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
    if (request.ok) {
      request.json().then(function (response) {
        //Se comprueba si se logro setear el id
        if (response.status) {
          //Se redirige a la página de la información del producto
          console.log(response.id_producto);
          location.href = 'info-producto.html';
        } else {
          sweetAlert(2, 'No se pudo redirigir a la información del producto', null);
        }
      });
    } else {
      console.log(request.status + ' ' + request.statusText);
    }
  });
}