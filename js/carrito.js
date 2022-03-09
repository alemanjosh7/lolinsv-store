var opcionesCarrito ={
    edge: 'right'
}
//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'),opcionesCarrito);
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
/*Ocultar el NavBar si se aprieta en seguir viendo*/
var btncontinuarv = document.getElementById('seguirv_carrito');
btncontinuarv.addEventListener('click', function(){
    let carrito = M.Sidenav.getInstance(document.querySelector('#carrito'));
    carrito.close();
});

/*

#carrito{
    font-family: 'Poppins',sans-serif;
    overflow: auto;
    height: 355px;
}

#parte-infocarrito{
    height: 55%;
    background-color: #F7F0E9;
    margin-top: 0;
    overflow: auto;
}  

.background{
    margin-bottom: 0;
    padding: 0;
    height: 90px;
}
#fondotitulo-carrito{
    height: 90px;
    width: 400px;
}

#footer-carrito{
    overflow: auto;
    margin: 0;
    padding: 0;
}
*/