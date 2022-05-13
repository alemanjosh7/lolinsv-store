// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ADMINS = SERVER + 'public/carrito.php?action=';
const API_GLBVAR = SERVER + 'variablesgb.php?action=';

/*Estilo de las opciones de los carritos y el navbar mobile*/
var carritoIni = {
    edge: "right",
    onOpenStart: function () {
        console.log('Hola');
    }
};
var navbarmobileC = {
    edge: "left",
};

//Iniciando algunos componentes y funciones
document.addEventListener('DOMContentLoaded', function () {
    //Inicializamos los componentes de Materialize
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    M.Sidenav.init(document.querySelectorAll("#mobile-demo"), navbarmobileC);
    M.Sidenav.init(document.querySelectorAll("#carrito"), carritoIni);
});

