/*
*   Controlador de uso general en las páginas web del sitio privado cuando se ha iniciado sesión.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para establecer la ruta y parámetros de comunicación con la API_HEADER_GLBVAR.
const API_HEADER = SERVER + 'variablesgb.php?action=';//Colocar la direccion correcta aqui

/*Estilo de las opciones de los carritos y el navbar mobile*/
var opcionesCarrito = {
    edge: "right",
    onOpenStart: function () {
        console.log('debería');
        hola();
    }
};
var navbarmobile = {
    edge: "left",
    onOpenStart: function () {
        console.log('debería');
        hola();
    }
};

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    fetch(API_HEADER + 'verificarCLLog', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si hay un status lo que significa que el cliente esta logueado
                if (response.status) {
                    const header = `
                    <div class="navbar-fixed">
                    <nav class="primary-bg-color">
                        <div class="container">
                            <div class="nav-wrapper">
                                <a href="perfil.html" class="brand-logo">
                                    <img class="logo-navbar" src="../../resources/img/icons/logo.png" alt="">
                                </a>
                                <a href="#carrito" data-target="mobile-demo" class="sidenav-trigger"><i
                                        class="material-icons">menu</i></a>
                                <ul class="right hide-on-med-and-down">
                                    <li>
                                        <a class="waves-effect waves-light btn second-bg-color boton"
                                            href="index.html">Inicio</a>
                                    </li>
                                    <li>
                                        <a class="waves-effect waves-light btn second-bg-color boton"
                                            href="productos.html">Productos</a>
                                    </li>
                                    <li>
                                    <li><a class="waves-effect waves-light btn second-bg-color boton" href="perfil.html">${response.usuario}<i class="material-icons left">person</i></a></li>
                                    </li>
                                    <li><a href="#carrito" data-target="carrito"
                                            class="sidenav-trigger show-on-large waves-effect waves-light btn second-bg-color boton"
                                            id="carrito-correcion"><img id="correcion-carrito-img" class="responsive-img"
                                                src="../../resources/img/amigurumis/shopping.png" alt=""></a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <ul class="sidenav" id="mobile-demo">
                    <li>
                        <a class="waves-effect waves-light btn second-bg-color boton" href="index.html">Inicio</a>
                    </li>
                    <li>
                        <a class="waves-effect waves-light btn second-bg-color boton" href="productos.html">Productos</a>
                    </li>
                    <li>
                    <li><a class="waves-effect waves-light btn second-bg-color boton" href="perfil.html">${response.usuario}<i
                                class="material-icons left">person</i></a></li>
                    </li>
                    <li><a href="#carrito" data-target="carrito"
                            class="sidenav-trigger show-on-large waves-effect waves-light btn second-bg-color boton"
                            id="abrircarrito-mobile"><img src="../../resources/img/amigurumis/shopping.png" alt=""></a></li>
                    </li>
                </ul>
                <!--Diseño del carrito-->
                <ul id="carrito" class="sidenav">
                    <!--Diseño del encabezado del carrito-->
                    <li>
                        <div class="fondodecoracion-carrito">
                            <div class="background">
                                <h4 id="titulo-carrito">Carrito</h4>
                                <img class="responsive-img" src="../../resources/img/icons/carrito-icono.png" id="carrito-icon">
                                <img class="responsive-img" src="../../resources/img/icons/fondo-carrito.png"
                                    id="fondotitulo-carrito">
                            </div>
                        </div>
                    </li>
                    <!--Diseño de la información del carrito-->
                    <li id="parte-infocarrito">
                        <div class="container">
                            <div class="row">
                                <form class="col s12" id="elemcarr-form">
                                    <!--Elementos de la compra en el carrito-->
                                    <!--Elemento-->
                                    <div class="row">
                                        <!--Fondo del elemento-->
                                        <div class="col s12" id="fondocompra-carrito1">
                                            <!--Icono de eliminar-->
                                            <div class="elimardel-carrito">
                                                <img class="responsive-img"
                                                    src="../../resources/img/icons/eliminardel-carrito.png" id="eliminar-prdcarr">
                                            </div>
                                            <!--Contenedor del contenido del carrito-->
                                            <div class="row" id="fila-contenidocarrito">
                                                <!--Columna de la imagen-->
                                                <div class="col s6 center-align" id="imagencontenido-carrito">
                                                    <img class="responsive-img"
                                                        src="../../resources/img/icons/amigurumi4-carrito.png" height="276px"
                                                        width="254">
                                                </div>
                                                <!--Columna del detalle del contenido-->
                                                <div class="col s6">
                                                    <!--Fila de los detalles-->
                                                    <div class="row">
                                                        <!--Columna del nombre del producto-->
                                                        <div class="col s12 center-align">
                                                            <h6 id="nombreAmigurumi_carrito">Brown Guy</h6>
                                                        </div>
                                                        <!--Columna de la cantidad-->
                                                        <div class="col s12" id="coldetalle-carrito">
                                                            <div class="col l1 s1 left-align">
                                                                <p><b>Cantidad:</b></p>
                                                            </div>
                                                            <div class="col l1 offset-l6 s1 offset-s6 right-align">
                                                                <p id="cantidaddetalle_carrito">2</p>
                                                            </div>
                                                        </div>
                                                        <!--Columna del precio-->
                                                        <div class="col s12" id="coldetalle-carrito">
                                                            <div class="col l1 s1 left-align">
                                                                <p><b>Precio:</b></p>
                                                            </div>
                                                            <div class="col l1 offset-l3 s1 offset-s5 right-align">
                                                                <p id="preciodetalle_carrito">$19.83</p>
                                                            </div>
                                                        </div>
                                                        <!--Columna del subtotal-->
                                                        <div class="col s12" id="coldetalle-carrito">
                                                            <div class="col l1 s1 left-align">
                                                                <p><b>Subtotal:</b></p>
                                                            </div>
                                                            <div class="col l1 offset-l4 s1 offset-s5 right-align">
                                                                <p id="preciodetalle_carrito">$39.66</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li id="footer-carrito">
                        <div class="row">
                            <div class="col s12">
                                <div class="col s4 right-align" id="total-carrito">
                                    <p>Total a pagar: </p>
                                </div>
                                <div class="col s1 left-align">
                                    <p id="pagot_carrito">$176.11</p>
                                </div>
                            </div>
                            <div class="col s12">
                                <div class="row carrito-opciones">
                                    <div class="col offset-s1 s5">
                                        <a href="confirmacion-compra.html" class="waves-effect waves-light btn"
                                            id="comprar_carrito">Comprar</a>
                                    </div>
                                    <div class="col s5">
                                        <a class="waves-effect waves-light btn"
                                            id="seguirv_carrito">Seguir Viendo</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                    `;
                    document.querySelector('header').innerHTML = header;
                    M.Sidenav.init(document.querySelectorAll(".sidenav"));
                    M.Sidenav.init(document.querySelectorAll("#mobile-demo"), navbarmobile);
                    M.Sidenav.init(document.querySelectorAll("#carrito"), opcionesCarrito);
                    /*Ocultar navbar mobile tras aparecer carrito*/
                    var btnabrircarrito = document.getElementById('abrircarrito-mobile');
                    btnabrircarrito.addEventListener('click', function () {
                        let navmobile = M.Sidenav.getInstance(document.querySelector('#mobile-demo'));
                        navmobile.close();
                    });
                    /*Ocultar el NavBar si se aprieta en seguir viendo*/
                    var btncontinuarv = document.getElementById('seguirv_carrito');
                    btncontinuarv.addEventListener('click', function () {
                        console.log('hola');
                        let carrito = M.Sidenav.getInstance(document.querySelector('#carrito'));
                        carrito.close();
                    });
                } else {
                    //Como no lo esta coloca el header normal
                    const header = `
                    <div class="navbar-fixed">
                    <nav class="primary-bg-color">
                        <div class="container">
                            <div class="nav-wrapper">
                                <a href="perfil.html" class="brand-logo">
                                    <img class="logo-navbar" src="../../resources/img/icons/logo.png" alt="">
                                </a>
                                <a href="#carrito" data-target="mobile-demo" class="sidenav-trigger"><i
                                        class="material-icons">menu</i></a>
                                <ul class="right hide-on-med-and-down">
                                    <li>
                                        <a class="waves-effect waves-light btn second-bg-color boton" href="index.html">Inicio</a>
                                    </li>
                                    <li>
                                        <a class="waves-effect waves-light btn second-bg-color boton" href="productos.html">Productos</a>
                                    </li>
                                    <li>
                                        <a class="waves-effect waves-light btn second-bg-color boton" href="registro-usuario.html" id="">Registrarse</a>
                                    </li>
                                    <li>
                                    <li><a class="waves-effect waves-light btn second-bg-color boton" href="login.html">Iniciar sesión <i
                                                class="material-icons left">person</i></a></li>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <ul class="sidenav" id="mobile-demo">
                    <li>
                        <a class="waves-effect waves-light btn second-bg-color boton" href="index.html">Inicio</a>
                    </li>
                    <li>
                        <a class="waves-effect waves-light btn second-bg-color boton" href="productos.html">Productos</a>
                    </li>
                    <li>
                        <a class="waves-effect waves-light btn second-bg-color boton"
                            href="registro-usuario.html">Registrarse</a>
                    </li>
                    <li>
                    <li><a class="waves-effect waves-light btn second-bg-color boton" href="login.html">Iniciar sesión <i
                                class="material-icons left">person</i></a></li>
                    </li>
                </ul>
                    `;
                    document.querySelector('header').innerHTML = header;
                    M.Sidenav.init(document.querySelectorAll(".sidenav"));
                    M.Sidenav.init(document.querySelectorAll("#mobile-demo"), navbarmobile);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});
