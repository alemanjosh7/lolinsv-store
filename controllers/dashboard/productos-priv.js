//Inicializando componentes de Materialize
document.addEventListener('DOMContentLoaded', function () {
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Slider.init(document.querySelectorAll('.slider'));
    M.Carousel.init(document.querySelectorAll('.carousel'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.FormSelect.init(document.querySelectorAll('select'));
    M.Modal.init(document.querySelectorAll('.modal'));
    botonAdelante.style.display = 'block';
});
/*Copiar número de Whatsaap en el Footer*/
function copiarWhat() {
    var content = document.getElementById('copywhat').innerHTML;
    navigator.clipboard.writeText(content)
}


document.querySelectorAll(".pagnavg").forEach(el => {
    el.addEventListener("click", e => {
        //Se obtiene el numero dentro del span
        let number =Number(el.textContent);
        //Se hace la operación para calcular cuanto será el top de elementos a no mostrarse en la consulta
        let topAct = (number*4)-4;
        console.log("Se ha clickeado el id "+number+" "+topAct);
        //Se ejecuta la recarga de datos enviando la variable de topAct
    });
});

//Evento cuando se precione el boton de ir atras y adelante en la páginación
var botonAtras = document.getElementById("pagnavg-atr");
var botonNumeroPagI = document.getElementById("pagnumeroi");
var botonNumeroPagF = document.getElementById("pagnumerof");
var botonAdelante = document.getElementById("pagnavg-adl");

//Boton de atras
botonAtras.addEventListener('click',function(){
    let paginaActual = Number(botonNumeroPagI.textContent);
    if(paginaActual!=1){
        botonNumeroPagI.innerHTML=Number(botonNumeroPagI.innerHTML)-2;
        botonNumeroPagF.innerHTML=Number(botonNumeroPagI.innerHTML)+1;
        if((Number(botonNumeroPagI.innerHTML)-1)==0){
            botonAtras.style.display = 'none';
        }
    }else{
    }
});

//Boton de adelante
botonAdelante.addEventListener('click',function(){
    botonAtras.style.display = 'block';
    let paginaFinal = (Number(botonNumeroPagF.innerHTML))+1;
    console.log("pagina maxima "+paginaFinal);
    /*Aqui se debe obtener si hay más datos o no, pueden usar una función que puede devolver un valor booleano
    deben realizar una operación sumando a la pagina final mostrada la cantidad de elementos que muestran y enviarlos para comprobar
    si hay datos entonces la variable será true, si no hay más datos entonces no se mostrará nada
    */
    let topAct = (paginaFinal*4)-4;
    console.log("el top a no mostrar sería "+paginaFinal)
    masDatos = true;
    
    if(botonAdelante.style.display != 'none'){
        console.log('no se esta mostrando')
    }

    /*if(botonAdelante.style.display != 'block'){
        botonNumeroPagI.innerHTML=Number(botonNumeroPagI.innerHTML)+2;
        botonNumeroPagF.innerHTML=Number(botonNumeroPagI.innerHTML)+1;
    }else{

    }*/
});

/*Ejemplo de la consulta para la navegación
select * from productos where id_producto
not in(select id_producto from productos order by id_producto limit 4) order by id_producto limit 4;

donde el limit dentro del not in sería la variable de pagNavegm
*/