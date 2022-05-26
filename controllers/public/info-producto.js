// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_GLBVAR = SERVER + 'variablesgb.php?action=';
//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    M.Modal.init(document.querySelectorAll('.modal'));
    obtenerIdProducto();
});
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
                    //Se redirige a la página de la información del producto
                    console.log(response.id_producto);
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
