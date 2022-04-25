/*Clase de Variables globales pueden haber varias o pocas, estas se manejarán en la mayoría de páginas publicas y privadas*/
//Variable del id del usuario/cliente
var idusrgb = null;
//Id de la compra 
var idcompragb = null;
//Nombre del cliente/usuario
var nombregb = null;
//Apellido del cliente/usuario
var apellidogb = null;
//Tipo de usuario
/*1=Admin 2=Empleado 3=Cliente*/
var tipousgb = null;

//Funciones para colocar los valores
//Función para el id del usuario
function setIdgb(id){
    idcompragb=id;
};

//Función para el id de la compra
function setIdcompragb(id){
    idcompragb=id;
};

//Función para nombre del cliente/usuario
function setNombregb(nm){
    nombregb = nm;
};

//Función para el apellido del cliente/usuario
function setApellidogb(nm){
    apellidogb = nm;
};

//Función para el tipo del usuario
function setTipousgb(nm){
    tipousgb = nm;
}
//Funciones para obtener las variables
//Obtener el id del usuario
function getIdgb(){
    return idusrgb;
};

//Obtener el id de la compra
function getCompragb(){
    return idcompragb;
};

//Obtener el nombre del usuario
function getNombreUsuario(){
    nombrecl = nombregb+" "+apellidogb;
    return nombrecl;
}

//Obtener el tipo de usuario
function getTipousgb(){
    return tipousgb;
}