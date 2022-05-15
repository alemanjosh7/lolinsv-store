//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
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