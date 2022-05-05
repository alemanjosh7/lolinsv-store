--DbLolin--
CREATE DATABASE dbLolin;
use dbLolin;
--Usuarios Clientes--
--Usuarios Admins--
--Admins--
CREATE TABLE admins(
	id_admin serial NOT null,
	nombre_admin varchar(100) NOT null,
	apellido_admin varchar(100) not null,
	usuario varchar(100) NOT NULL unique,
	contrasena varchar(500) NOT NULL,
	constraint admins_pk primary key(id_admin)
);
--Cliente--
CREATE TABLE clientes(
	id_cliente serial,
	nombre_cliente varchar(100) not null,
	apellido_cliente varchar(100) not null,
	correo_cliente varchar(100) not null unique,
	dui_cliente varchar(10) not null unique,
	telefono_cliente varchar(9) not null unique,
	direccion_cliente varchar(500) not null,
	usuario varchar(100) NOT NULL unique,
	contrasena varchar(500) NOT NULL,
	constraint cliente_pk primary key(id_Cliente)
);
--Tamaños--
create table tamanos(
	id_tamanos serial not null,
	tamano varchar(150) not null unique,
	CONSTRAINT tamanos_pk PRIMARY KEY(id_tamanos)
);
--Estados--
create table estados(
	id_estados serial not null,
	estado varchar(150) not null unique,
	CONSTRAINT estados_pk PRIMARY KEY(id_estados)
);
--Pedidos Personalizados
CREATE TABLE pedidos_personalizados(
	id_pedidos_personalizado serial NOT NULL,
	fecha_pedidopersonal date NOT NULL,
	descripcion_pedidopersonal varchar(500) NOT NULL,
	imagenejemplo_pedidopersonal varchar(500) NOT NULL,
	descripcionlugar_entrega varchar(500)NOT NULL,
	fk_id_cliente integer NOT NULL,
	fk_id_tamano integer NOT NULL,
	fk_id_estado integer NOT NULL,
	CONSTRAINT pedidos_personalizados_pk PRIMARY KEY (id_pedidos_personalizado),
	CONSTRAINT pedidos_personalizados_clientes_fk FOREIGN KEY (fk_id_cliente) REFERENCES clientes(id_cliente) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT pedidos_personalizados_tamanos_fk FOREIGN KEY (fk_id_tamano) REFERENCES tamanos(id_tamanos) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT pedidos_personalizados_estados_fk FOREIGN KEY (fk_id_estado) REFERENCES estados(id_estados) ON UPDATE CASCADE ON DELETE CASCADE
);
--Pedidos Establecidos
create table pedidos_establecidos(
	id_pedidos_establecidos serial not null,
	fecha_pedidoesta date not null,
	descripcionlugar_entrega varchar(500) not null,
	montototal_pedidoesta numeric(6,2) not null,
	fk_id_cliente int not null,
	fk_id_estado int not null,
	constraint pedidosesta_pk primary key (id_pedidos_establecidos),
	constraint pedidosesta_clientes_fk foreign key (fk_id_cliente) references clientes(id_cliente) on update cascade on delete cascade,
	constraint pedidosesta_estados_fk foreign key (fk_id_estado) references estados(id_estados) on update cascade on delete cascade
);
--Categorias--
create table categorias(
	id_categoria serial NOT NULL,
	nombre_categoria varchar(100) not null unique,
	CONSTRAINT categorias_pk PRIMARY KEY (id_categoria)
);
--Valoracion--
create table valoraciones(
	id_valoraciones serial NOT NULL,
	valoraciones integer not null unique,
	CONSTRAINT valoraciones_pk PRIMARY KEY (id_valoraciones)
);
--Productos--
CREATE table productos(
	id_producto serial NOT NULL,
	nombre_producto varchar(150)  NOT NULL unique,
	imagen_producto varchar(500)  NOT NULL,
	precio_producto numeric(6,2) NOT NULL,
	cantidad integer NOT NULL,
	descripcion VARCHAR(150) NOT NUll,
	fk_id_categoria integer NOT NULL,
	fk_id_valoraciones integer NOT NULL,
	fk_id_admin integer NOT NULL,
	CONSTRAINT productos_pk PRIMARY KEY (id_producto),
	CONSTRAINT productos_categorias_fk FOREIGN KEY (fk_id_categoria) REFERENCES categorias(id_categoria) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT productos_valoraciones_fk FOREIGN KEY (fk_id_valoraciones) REFERENCES valoraciones(id_valoraciones) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT productos_admin_fk FOREIGN KEY (fk_id_admin) REFERENCES admins(id_admin) ON UPDATE CASCADE ON DELETE CASCADE
);
--Detalle Pedido Establecido--
create table detallepedidos_establecidos(
	id_detalle_pedidos serial not null,
	cantidad_detallep int not null,
	subtotal_detallep numeric(6,2) not null,
	fk_id_producto int not null,
	fk_id_pedidos_establecidos int not null,
	constraint detallepedidos_pk primary key (id_detalle_pedidos),
	constraint detallepedidos_productos_fk foreign key (fk_id_producto) references productos(id_producto) on update cascade on delete cascade,
	constraint detallepedidos_pedidosesta_fk foreign key (fk_id_pedidos_establecidos) references pedidos_establecidos(id_pedidos_establecidos) on update cascade on delete cascade
);
--Valoraciones Clientes--
CREATE TABLE valoraciones_clientes(
	id_valoracionescli serial NOT NULL,
	comentario varchar(500) NOT NULL,
	fk_id_cliente integer NOT NULL,
	fk_id_productos integer NOT NULL,
	fk_id_valoraciones integer NOT NULL,
	CONSTRAINT valoraciones_clientes_pk PRIMARY KEY (id_valoracionescli),
	CONSTRAINT valoraciones_cliente_fk FOREIGN KEY (fk_id_cliente) REFERENCES clientes (id_cliente) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT valoraciones_clientes_productos_fk FOREIGN KEY (fk_id_productos) REFERENCES productos(id_producto) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT valoraciones_clientes_valoraciones_fk FOREIGN KEY (fk_id_valoraciones) REFERENCES valoraciones(id_valoraciones) ON UPDATE CASCADE ON DELETE CASCADE
);
create table inventario(
	id_inventario serial not null,
	cantidada int not null,
	cantidadn int not null,
	modificado boolean not null,
	fecha date default current_date,
	fk_id_admin int not null,
	fk_id_producto int not null,
	constraint inventario_pk primary key (id_inventario),
	constraint inventario_admins_fk foreign key (fk_id_admin) references admins(id_admin) on update cascade on delete cascade,
	constraint inventario_productos_fk foreign key (fk_id_producto) references productos(id_producto) on update cascade on delete cascade
);
--Fin por el momento--
--Insert de las tablas--
--Administradores--
truncate table admins RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

Insert into admins (nombre_admin, apellido_admin, usuario, contrasena)
values ('Jesus', 'Esquivel', 'JesusEs', 'Jesus123'),
('Josue', 'Aleman', 'AlemanJos', 'Aleman2246'),
('Isabel', 'Martinez', 'IsabelM', 'IsabeJM'),
('Jonathan', 'Grande', 'JonthanR', 'Grande46'),
('Heber', 'Cornejo', 'CornejoHb', 'Heber2791');

select * from admins;--Comprobar--
--Categoria--
truncate table categorias RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

Insert into categorias (nombre_categoria) values
('Animales'),
('Personajes'),
('Caricatura'),
('Videojuegos'),
('Plantas'),
('Accesorio para humano'),
('Anime'),
('Decoración de oficina'),
('Florales'),
('Película'),
('Artista');

select * from categorias;--Comprobar--
--Valoraciones--
truncate table valoraciones RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

Insert into valoraciones (valoraciones)
values (1),
(2),
(3),
(4),
(5);

select * from valoraciones;--comprobación
--Tamaños--Insert into valoraciones (valoraciones)
values (1),
(2),
(3),
(4),
(5);
truncate table tamanos RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

Insert into tamanos(tamano)
values ('Grande:21cm a 35cm'),
('Mediano:13cm a 20cm'),
('Pequeño:12cm o menos');

select * from tamanos;--comprobación
--Estados--
truncate table estados RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

Insert into estados (estado)
values ('Pendiente'),
('Enviado'),
('Agotado'),
('Disponible'),
('Negado'),
('Aceptado');

select * from estados;--comprobación
--Clientes--
truncate table clientes RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

INSERT INTO clientes (nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, 
telefono_cliente, direccion_cliente, usuario, contrasena)values
('Carlos', 'Fuentes', 'CarlosFfu@gmail.com', '09452339-3', '7327-7323', 'DOMICILIO 
CONOCIDO', 'Fuentesc82', '876120'),--1--
('Sara', 'Pinar', 'Valentina.Ros37@gmail.com', '98373359-3', '7087-2455', 'AV.
GUADALUPE S/N', 'Valentina.Ros37', '098475'),--2--
('Martín', 'Acosta', 'Óscar.Ren34@gmail.com', '98072540-5', '6078-2340', 'AVENIDA 
NIÑOS HEROES NO. 3', 'Óscar.Ren34', '805235'),--3--
('Gabriela', 'Torre', 'Sara.Pin96@gmail.com', '09287455-4', '6976-3094', 'CARRETERA 
MEXICO-LAREDO KM.125', 'Sara.Pin96', '101345'),--4--
('Matías', 'Rincón', 'Efraín.Mej52@gmail.com', '10946555-8', '7623-9054', 'PLAZA 
CONSTITUCION NO. 1', 'Efraín.Mej52', '398360'),--5--
('Manuela', 'Hincapié', 'Julieta.Leó07@gmail.com', '67685809-9', '7726-0832', 
'DOMICILIO CONOCIDO', 'Julieta.Leó07', '087345'),--6--
('Sebastián', 'Yepes', 'Martín.Aco68@gmail.com', '08207945-7', '7234-5999', 
'CARRETERA MEXICO-LAREDO', 'Martín.Aco68', '089367'),--7--
('Sofía', 'Arango', 'Gabriela.Tor11@gmail.com', '90827633-3', '7980-2387', 'AVENIDA 
MIGUEL HIDALGO S/N', 'Gabriela.Tor11', '098734'),--8--
('Ana', 'Peña', 'Matías.Rin93@gmail.com', '98745067-1', '7107-2044', 'CARRETERA SAN 
SALVADOR SAN MIGUEL K', 'Matías.Rin93', '309i94'),--9--
('Mónica', 'Mendoza', 'Manuela.Hin07@gmail.com', '97605629-9', '6657-3924', 'AV. 16 
DE JULIO S/N', 'Manuela.Hin07', 'a09843');--10--

select * from clientes;--comprobación
--Productos--
truncate table productos RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

insert into productos(nombre_producto, imagen_producto, precio_producto,--1-- 
					 cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
					 VALUES ('Hello Kitty', 'hellokitty.png', 25.00, 35, 'Peluche hecho 100% de algodón', 2, 5,1);
insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Ratones', 'ratones.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Cerdita', 'cerdita.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Snoopy', 'snoopy.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);


insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Mario Bros', 'mariobros.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Cactus', 'cactus.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Patito', 'patito.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Mafalda', 'mafalda.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Harry Potter', 'harrypotter.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

insert into productos
	(nombre_producto, imagen_producto, precio_producto,--1-- 
	cantidad, descripcion, fk_id_categoria, fk_id_valoraciones,fk_id_admin)
VALUES
	('Jirafas con Rosa', 'jirafarosa.png', 'Peluche hecho 100% de algodón', 25.00, 35, 2, 5, 1);

select * from productos;--comprobación
--Pedido establecido--
truncate table pedidos_establecidos RESTART IDENTITY cascade;--Reiniciando id en caso sea necesario--

insert into pedidos_establecidos(fecha_pedidoesta,descripcionlugar_entrega,
			montototal_pedidoesta,fk_id_cliente,fk_id_estado) values
			('2022/03/3','Tercer Parqueo, calle principal frente a unas champas',
			 25.30,1,1),--1--
			 ('2022/03/2','Tercer Parqueo, calle principal frente a unas champas',
			 24.30,2,1),--2--
			 ('2022/03/1','Tercer Parqueo, calle principal frente a unas champas',
			 23.30,3,1),--3--
			 ('2022/02/28','Segundo Parqueo, calle principal frente a unas champas',
			 22.30,4,1),--4--
			 ('2022/02/27','Segundo Parqueo, calle principal frente a unas champas',
			 21.30,5,1),--5--
			 ('2022/02/26','Segundo Parqueo, calle principal frente a unas champas',
			 20.50,6,1),--6--
			 ('2022/02/25','Primer Parqueo, calle principal frente a unas champas',
			 19.25,7,1),--7--
			 ('2022/02/24','Primer Parqueo, calle principal frente a unas champas',
			 18.50,8,1),--8--
			 ('2022/02/23','Primer Parqueo, calle principal frente a unas champas',
			 15.00,9,1),--9--
			 ('2022/02/22','casa frente de color azul frente al Ricaldone',
			 10.13,10,1);--10--
			 
select * from pedidos_establecidos;--Comprobación
--detallepedidos_establecidos--
truncate table detallepedidos_establecidos RESTART IDENTITY ;--Reiniciando id en caso sea necesario--

insert into detallepedidos_establecidos(cantidad_detallep,subtotal_detallep,
			fk_id_producto,fk_id_pedidos_establecidos) values
			(1,8.44,1,1),--1--
			(2,8.43,2,1),--2--
			(3,8.43,3,1),--3--
			(4,8.10,4,2),--4--
			(5,8.10,5,2),--5--
			(6,8.10,6,2),--6--
			(7,7.78,7,3),--7--
			(8,7.76,8,3),--8--
			(9,7.76,9,3),--9--
			(10,7.44,10,4),--10--
			(11,7.43,1,4),--11--
			(12,7.43,2,4),--12--
			(13,7.10,3,5),--13--
			(14,7.10,4,5),--14--
			(15,7.10,5,5),--15--
			(16,6.84,6,6),--16--
			(17,6.83,7,6),--17--
			(18,6.83,8,6),--18--
			(19,6.43,9,7),--19--
			(20,6.41,10,7),--20--
			(21,6.41,1,7),--21--
			(22,6.18,2,8),--22--
			(23,6.16,3,8),--23--
			(24,6.16,4,8),--24--
			(25,5.00,5,9),--25--
			(26,5.00,6,9),--26--
			(27,5.00,7,9),--27--
			(28,3.39,8,10),--28--
			(29,3.37,9,10),--29--
			(30,3.37,10,10);--30--
select * from detallepedidos_establecidos;--Comprobación--
--Etapa de Ejecución--
--Funciones
--Función para devolver el id de un empleado para el Login
--Creamos la función con la consulta dentro
CREATE OR REPLACE FUNCTION obtenerIdEmpleado(usu varchar, contra varchar) returns 
Table("id" int)
AS 
$$
DECLARE
BEGIN
RETURN QUERY SELECT ad.id_admin FROM admins as ad
where ad.usuario = usu and ad.contrasena = contra;
END
$$
LANGUAGE plpgsql; 
 
select * from obtenerIdEmpleado('JesusEs', 'Jesus123');
select * from admins;
--Función para insertar un producto
CREATE OR REPLACE FUNCTION insertarProducto(nombre_p varchar, imagen_p varchar, precio_p decimal, 
											cantidad_p int, categoria_p int, valoraciones_p int, 
											id_admin_p int) returns 
VOID
AS 
$$
BEGIN
INSERT INTO productos VALUES (default, nombre_p, imagen_p, precio_p, cantidad_p, categoria_p, valoraciones_p, id_admin_p);
END
$$
LANGUAGE plpgsql; 
select * from productos;
select insertarProducto('Papu Poh', 'imagen/papu', 15.00, 35, 1, 5, 1);
 
--Función para ingresar un administrador
CREATE OR REPLACE FUNCTION insertarAdministrador(nombre_a varchar, apellido_a varchar, 
												 usuario_a varchar, contra varchar) returns 
VOID
AS 
$$
BEGIN
INSERT INTO admins VALUES (default, nombre_a, apellido_a, usuario_a, contra);
END
$$
LANGUAGE plpgsql; 
select * from admins;
select insertarAdministrador('Gerardo', 'Martinez', 'GMartinez', '123GMA');
--Group by, Order By,Beetween
--Agrupar los productos por categoria
--Group by--
--agrupar productos por categoria--
SELECT nombre_producto,nombre_categoria FROM productos,	categorias GROUP BY nombre_producto,
   nombre_categoria;
--Order by
--Ordenar los productos ordenandolos por cantidad de forma descendiente
--order by--
--productos por cantidad  descendiente--
SELECT nombre_producto,cantidad FROM productos ORDER BY cantidad DESC;
--Productos entre 10 dolares y 25 dolares
SELECT * FROM "productos" WHERE  "precio_producto" BETWEEN '10' and '25' order by precio_producto;
--TRIGGERS
--Que actualice monto total del pedido luego de cada insert en el detalle de dicho 
--pedido Se le sumará al monto total el subtotal nuevo
--Primero se ejecuta la función que el trigger realizara
CREATE FUNCTION actualizarMontoPedido() RETURNS TRIGGER
AS
$$
DECLARE 
--Declaramos las variables
idmax int:=(SELECT MAX(id_detalle_pedidos) FROM detallepedidos_establecidos);
idpedido int:=(SELECT fk_id_pedidos_establecidos FROM detallepedidos_establecidos 
			   WHERE id_detalle_pedidos=idmax);
preciot numeric(6,2):=(SELECT SUM(subtotal_detallep) FROM detallepedidos_establecidos
					  WHERE fk_id_pedidos_establecidos=idpedido);
BEGIN
--Indicamos el procedimiento a realizar
UPDATE pedidos_establecidos SET montototal_pedidoesta=preciot 
WHERE id_pedidos_establecidos=idpedido;
RETURN NEW;
END
$$
LANGUAGE plpgsql;--Final del la función del trigger
--Inicio del trigger
CREATE TRIGGER tr_actualizarmonto AFTER INSERT ON detallepedidos_establecidos
FOR EACH ROW
EXECUTE PROCEDURE actualizarMontoPedido();--Final del Trigger
--Probamos el trigger insertando en el detalle y mostrando la tabla de pedido
SELECT SUM(subtotal_detallep) FROM detallepedidos_establecidos--Monto inicial
					  WHERE fk_id_pedidos_establecidos=10
INSERT INTO detallepedidos_establecidos(cantidad_detallep,subtotal_detallep,
			fk_id_producto,fk_id_pedidos_establecidos) VALUES
			(1,25.00,1,10);
SELECT SUM(subtotal_detallep) FROM detallepedidos_establecidos--Monto comprobado
					  WHERE fk_id_pedidos_establecidos=10
--Cada vez que se añada un detalle a un pedido la cantidad del producto se le restará a 
--la cantidad del producto en la tabla de productos
--Primero se ejecuta la función que el trigger realizara
CREATE FUNCTION actualizarCantidadProdct() RETURNS TRIGGER
AS
$$
DECLARE 
--Declaramos las variables
idmax int:=(SELECT MAX(id_detalle_pedidos) FROM detallepedidos_establecidos);
ctr int:=(SELECT cantidad_detallep FROM detallepedidos_establecidos 
		  WHERE id_detalle_pedidos=idmax);
idprd int:=(SELECT fk_id_producto FROM detallepedidos_establecidos 
			   WHERE id_detalle_pedidos=idmax);
BEGIN
--Indicamos el procedimiento a realizar
UPDATE productos SET cantidad=cantidad-ctr WHERE id_producto=idprd;
RETURN NEW;
END
$$
LANGUAGE plpgsql;--Final del la función del trigger
--Inicio del trigger
CREATE TRIGGER tr_actualizarcantidadprd AFTER INSERT ON detallepedidos_establecidos
FOR EACH ROW
EXECUTE PROCEDURE actualizarCantidadProdct();--Final del Trigger
--Probamos el trigger insertando en el detalle y mostrando la tabla de producto 
--verificando que la cantidad de este resto
SELECT cantidad FROM productos WHERE id_producto=9;
INSERT INTO detallepedidos_establecidos(cantidad_detallep,subtotal_detallep,
			fk_id_producto,fk_id_pedidos_establecidos) VALUES
			(1,15.00,9,10);
SELECT cantidad FROM productos WHERE id_producto=9;
--Cada vez que se ingrese una valoración del cliente sobre un producto, se actualizará 
--la valoración del producto a la valoración más repetida.
--Hacemos una inserción en la tabla de valoraciones_clientes para demostración

INSERT INTO valoraciones_clientes (comentario, fk_id_cliente, fk_id_productos,
								   fk_id_valoraciones) values
								   ('Gran producto',1,1,5);
SELECT * FROM valoraciones_clientes;
--Primero se ejecuta la función que el trigger realizara
CREATE FUNCTION actualizarValoracion() RETURNS TRIGGER
AS
$$
DECLARE 
--Declaramos las variables
idmax int:=(select max(id_valoracionescli)from valoraciones_clientes);
idpt int:=(select fk_id_productos from valoraciones_clientes where id_valoracionescli=idmax);
idv1 int:=(select count(fk_id_valoraciones) as total from valoraciones_clientes 
		   where fk_id_productos = idpt and fk_id_valoraciones =1);
idv2 int:=(select count(fk_id_valoraciones) as total from valoraciones_clientes 
		   where fk_id_productos = idpt and fk_id_valoraciones =2);
idv3 int:=(select count(fk_id_valoraciones) as total from valoraciones_clientes 
		   where fk_id_productos = idpt and fk_id_valoraciones =3);
idv4 int:=(select count(fk_id_valoraciones) as total from valoraciones_clientes 
		   where fk_id_productos = idpt and fk_id_valoraciones =4);
idv5 int:=(select count(fk_id_valoraciones) as total from valoraciones_clientes 
		   where fk_id_productos = idpt and fk_id_valoraciones =5);
idv int:=3;
BEGIN
--Indicamos el procedimiento a realizar
case --Colocamos el valor en  cada caso
when (idv1>idv2 and idv1>idv3 and idv1>idv4 and idv1>idv5) then
	idv=1;
when (idv2>idv1 and idv2>idv3 and idv2>idv4 and idv2>idv5) then
	idv=2;
when (idv3>idv2 and idv3>idv1 and idv3>idv4 and idv3>idv5) then
	idv=3;
when (idv4>idv2 and idv4>idv3 and idv4>idv1 and idv4>idv5) then
	idv=4;
when (idv5>idv2 and idv5>idv3 and idv5>idv4 and idv5>idv1) then
	idv=5;
end case;
UPDATE productos SET fk_id_valoraciones=idv WHERE id_producto=idpt;
RETURN NEW;
END
$$
LANGUAGE plpgsql;--Final del la función del trigger
--Inicio del trigger
CREATE TRIGGER tr_actualizarvaloracion AFTER INSERT ON valoraciones_clientes
FOR EACH ROW
EXECUTE PROCEDURE actualizarValoracion();--Final del Trigger
--Comprobamos trallendo la valoración del producto al que se le creo una valoración.
select * from valoraciones_clientes;--Se le hizo al producto 1
--Comprobamos la valoración original del producto
select prd.nombre_producto, val.valoraciones from valoraciones as val,productos 
as prd where prd.fk_id_valoraciones=val.id_valoraciones AND
prd.id_producto=1;  
--Realizamos dos inserciones con una valoración de 4 por distintos clientes en el 
--mismo producto
INSERT INTO valoraciones_clientes (comentario, fk_id_cliente, fk_id_productos,
								   fk_id_valoraciones) values
								   ('Meh de producto',2,1,4),
								   ('Guto producto',3,1,4);
--Comprobamos la valoración nueva
select prd.nombre_producto, val.valoraciones from valoraciones as val,productos 
as prd where prd.fk_id_valoraciones=val.id_valoraciones AND
prd.id_producto=1;--Funciono--
--Operaciones aritmeticas
--Contar cuantos productos hay de cada categoría
create view detalle_productos
as
select nombre_producto, precio_producto, id_categoria
from productos tp, categorias tc
where tp.fk_id_categoria = tc.id_categoria; 

SELECT COUNT (*) FROM detalle_productos WHERE id_categoria = 2;--Comprobamos
--Obtener el precio de un producto calculado de la cantidad de este por su precio 
--en el detalle del pedido establecido agrupado por el pedido establecido a que le pertenece
create view total_produc 
as
select nombre_producto, precio_producto, cantidad_detallep, id_detalle_pedidos
from productos tp, detallepedidos_establecidos td
where td.fk_id_producto = tp.id_producto;

select  nombre_producto, id_detalle_pedidos, cantidad_detallep*precio_producto
as total
from total_produc;
--Contar la cantidad de productos registrado por cada administrador
create view producto_admi
as
select nombre_producto, nombre_admin, id_admin
from productos tp, admins ta
where tp.fk_id_admin = ta.id_admin;

select count (*) from producto_admi where id_admin = 6;--Comprobamos
--Joins--
--Mostrar los productos, cantidad en la compra, subtotal de estos y 
--el cliente relacionados a una compra
SELECT pro.id_producto,pro.nombre_producto, dte.cantidad_detallep, dte.subtotal_detallep, 
pes.id_pedidos_establecidos,(clt.nombre_cliente,clt.apellido_cliente)as cliente
FROM productos AS pro
INNER JOIN detallepedidos_establecidos AS dte ON pro.id_producto = dte.fk_id_producto
INNER JOIN pedidos_establecidos AS pes ON dte.fk_id_pedidos_establecidos = pes.id_pedidos_establecidos
INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente;
--Mostrar los productos registrados junto con su categoria, valoración y el nombre 
--con el apellido del administrador
SELECT pro.id_producto,pro.nombre_producto,cat.nombre_categoria,val.valoraciones,
(adm.nombre_admin,adm.apellido_admin)as administrador
FROM productos AS pro
INNER JOIN categorias AS cat ON pro.fk_id_categoria = cat.id_categoria
INNER JOIN valoraciones AS val ON pro.fk_id_valoraciones = val.id_valoraciones
INNER JOIN admins AS adm ON pro.fk_id_admin = adm.id_admin
ORDER BY pro.id_producto;
--Contar cuantas compras ha hecho un cliente incluso si no ha hecho
SELECT COUNT(pes.fk_id_cliente) AS compras,
(clt.nombre_cliente, clt.apellido_cliente) as cliente,clt.id_cliente
FROM clientes AS clt
RIGHT JOIN pedidos_establecidos AS pes ON clt.id_cliente = pes.fk_id_cliente
GROUP BY clt.id_cliente ORDER BY clt.id_cliente;
--Mostrar las compras junto con su cliente y el estado en el que se encuentran
SELECT pes.id_pedidos_establecidos, est.estado,
(clt.nombre_cliente,clt.apellido_cliente) as cliente,clt.id_cliente
FROM pedidos_establecidos as pes
INNER JOIN estados AS est ON pes.fk_id_estado = est.id_estados
INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
ORDER BY clt.id_cliente;
--Updates
--update de tabla productos
update productos set precio_producto = 25.00 where id_producto = 1;
--update de la tabla de admin
select * from admins;
update admins set nombre_admin='Jesús' where id_admin=1;
--update de la tabla de categorias
select * from categorias;
           update categorias set nombre_categoria='Peliculas' where id_categoria=10;
--Consultas con parametros
--Función para buscar productos de x categoria
CREATE OR REPLACE FUNCTION obtenerProductosCategoria(categ varchar) returns 
Table("id" int, "nombre del producto" varchar, "imagen del producto" varchar, 
      "precio del producto" numeric(6,2),"cantidad" int, "valoraciones" int )
AS 
$$
DECLARE
BEGIN
RETURN QUERY SELECT p.id_producto, p.nombre_producto, p.imagen_producto, p.precio_producto, 
p.cantidad, p.fk_id_valoraciones FROM productos as p INNER JOIN categorias AS cate ON 
cate.nombre_categoria = categ and cate.id_categoria = p.fk_id_categoria;
END
$$
LANGUAGE plpgsql; 

select ObtenerProductosCategoria('Animales');--Comprobar
--Los productos registrado por (X) administrador
select tp.nombre_producto, tp.cantidad, tp.precio_producto, tp.imagen_producto, ta.nombre_admin, 
ta.apellido_admin from productos tp, admins ta where tp.fk_id_admin = ta.id_admin
and nombre_admin='Jesus' and apellido_admin='Esquivel';
--Creamos la función que devuelva el select
--Inicio de la función que retorne una tabla
CREATE FUNCTION productosRegAdmin(nombre varchar, apellido varchar) returns
--Indicamos las columnas y sus valores
Table("producto" varchar(150), "cantidad" integer, "precio del producto" numeric(6,2), 
	  "imagen del producto" varchar(500),"nombre administrador" varchar(100), 
	  "apellido administrador" varchar(100))
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY select tp.nombre_producto, tp.cantidad, tp.precio_producto, tp.imagen_producto, ta.nombre_admin, 
ta.apellido_admin from productos tp, admins ta where tp.fk_id_admin = ta.id_admin
and nombre_admin=nombre and apellido_admin=apellido;
END
$$
LANGUAGE plpgsql; 
--COMPROBAMOS
select * from productos
SELECT * FROM productosRegAdmin('Jesús','Esquivel');
--Seleccionar los productos entre un rango dos rangos de precio Los rangos son el parametro
CREATE FUNCTION rangoPrecioProducto(precioi int, preciof int) returns
--Indicamos las columnas y sus valores
Table("producto" varchar(150),"precio del producto" numeric(6,2))
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY SELECT nombre_producto,	precio_producto FROM productos WHERE precio_producto >=precioi 
AND precio_producto<=preciof ORDER BY precio_producto DESC;
END
$$
LANGUAGE plpgsql;
SELECT * FROM rangoPrecioProducto(10,15);
--Seleccionar los productos entre un rango dos rangos de precio Los rangos son el parametro
CREATE FUNCTION rangoPrecioProducto(precioi int, preciof int) returns
--Indicamos las columnas y sus valores
Table("producto" varchar(150),"precio del producto" numeric(6,2))
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY SELECT nombre_producto,	precio_producto FROM productos WHERE precio_producto >=precioi 
AND precio_producto<=preciof ORDER BY precio_producto DESC;
END
$$
LANGUAGE plpgsql;
SELECT * FROM rangoPrecioProducto(15,25);
--Seleccionar los productos por x Categoria
--Creamos la función que retorne el select
CREATE FUNCTION obtenerproductosXCategoria(categoria varchar) returns
--Indicamos las columnas y sus valores
Table("producto" varchar(150),"nombre de categoria" varchar(100),"id de categoria" integer)
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY SELECT pt.nombre_producto,	cat.nombre_categoria,cat.id_categoria FROM productos as pt, 
categorias as cat WHERE cat.nombre_categoria=categoria and pt.fk_id_categoria=cat.id_categoria 
ORDER BY nombre_categoria;
END
$$
LANGUAGE plpgsql;
select * from categorias
SELECT * FROM obtenerproductosXCategoria('Personajes');
--El mínimo de valoración de un producto X
Select MIN(vcl.fk_id_valoraciones),pct.nombre_producto from valoraciones_clientes as vcl, productos as
pct where vcl.fk_id_productos=pct.id_producto and pct.nombre_producto='Hello Kitty' 
group by pct.nombre_producto;
--Creamos la función que retorne el select
CREATE FUNCTION minimoValoracionProducto(producto varchar) returns
--Indicamos las columnas y sus valores
Table("Valoracion" integer,"nombre del producto" varchar(150))
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY Select MIN(vcl.fk_id_valoraciones),pct.nombre_producto from valoraciones_clientes as vcl,
productos as pct where vcl.fk_id_productos=pct.id_producto and pct.nombre_producto=producto
group by pct.nombre_producto;
END
$$
LANGUAGE plpgsql;
select * from productos
SELECT * FROM minimoValoracionProducto('Ratones');
--CONSULTAS CON RANGOS DE FECHAS PARAMETRIZADOS
--Pedidos establecidos antes de X fecha
--Creamos la función que devuelva el select
--Inicio de la función que retorne una tabla
CREATE FUNCTION pedidosAntesDE(fecha date) returns 
--Indicamos las columnas y sus valores
Table("id" int, "fecha del pedido" date, "descripcion" varchar, 
	  "monto total" numeric(6,2),"cliente" record, "estado" varchar)
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY SELECT pes.id_pedidos_establecidos, pes.fecha_pedidoesta, 
pes.descripcionlugar_entrega,pes.montototal_pedidoesta,(clt.nombre_cliente,
clt.apellido_cliente) as cliente,est.estado FROM pedidos_establecidos as pes
INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
INNER JOIN estados AS est ON pes.fk_id_estado = est.id_estados 
WHERE pes.fecha_pedidoesta<=fecha 
order by pes.fecha_pedidoesta desc;
END
$$
LANGUAGE plpgsql; 
--COMPROBAMOS
SELECT * FROM pedidosAntesDE('2022-02-28');
--Pedidos establecidos dentro de X y Y fechas
CREATE FUNCTION pedidosEntreFechas(fechai date, fechaf date) returns 
--Indicamos las columnas y sus valores
Table("id" int, "fecha del pedido" date, "descripcion" varchar, 
	  "monto total" numeric(6,2),"cliente" record, "estado" varchar)
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY SELECT pes.id_pedidos_establecidos, pes.fecha_pedidoesta, 
pes.descripcionlugar_entrega,pes.montototal_pedidoesta,(clt.nombre_cliente,
clt.apellido_cliente) as cliente,est.estado FROM pedidos_establecidos as pes
INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
INNER JOIN estados AS est ON pes.fk_id_estado = est.id_estados 
WHERE pes.fecha_pedidoesta>=fechai AND pes.fecha_pedidoesta<=fechaf 
order by pes.fecha_pedidoesta desc;
END
$$
LANGUAGE plpgsql; 
--COMPROBAMOS
SELECT * FROM pedidosEntreFechas('2022-02-24','2022-03-02');
--Pedidos personalizados dentro de X y Y fechas
--Insertamos en la tabla de pedidos personalizados
INSERT INTO pedidos_personalizados (fecha_pedidopersonal,descripcion_pedidopersonal,
									imagenejemplo_pedidopersonal,
									descripcionlugar_entrega,fk_id_cliente,fk_id_tamano,
								   fk_id_estado) values
('2022-02-24','muñeco de superman','imagen/pedido/hola','frente a un chalet verde',1,2,1),
('2022-03-10','muñeco de batman','imagen/pedido/batman','frente a un chalet naranja',
 2,2,1),
('2022-03-17','muñeco de robin','imagen/pedido/robin_png','frente a un chalet azul',2
 ,2,1);
 --Creamos la función con la consulta dentro
CREATE FUNCTION pedidosPerEntreFechas(fechai date, fechaf date) returns 
--Indicamos las columnas y sus valores
Table("id" int, "fecha del pedido" date, "descripcion" varchar, 
	  "imagen de ejemplo" varchar,"descripcion lugar entrega" varchar, "cliente" record,
	 "tamano" varchar, "estado" varchar)
AS 
$$
DECLARE
BEGIN
--Retornamos el select
RETURN QUERY SELECT pes.id_pedidos_personalizado, pes.fecha_pedidopersonal, 
pes.descripcion_pedidopersonal,pes.imagenejemplo_pedidopersonal,
pes.descripcionlugar_entrega,(clt.nombre_cliente,clt.apellido_cliente) as cliente,
tmn.tamano,est.estado FROM pedidos_personalizados as pes
INNER JOIN clientes AS clt ON pes.fk_id_cliente = clt.id_cliente
INNER JOIN tamanos as tmn ON pes.fk_id_tamano = tmn.id_tamanos
INNER JOIN estados AS est ON pes.fk_id_estado = est.id_estados 
WHERE pes.fecha_pedidopersonal>=fechai AND pes.fecha_pedidopersonal<=fechaf
order by pes.fecha_pedidopersonal desc;
END
$$
LANGUAGE plpgsql; 
--COMPROBAMOS
SELECT * FROM pedidosPerEntreFechas('2022-02-24','2022-03-29');

select * from productos where id_producto 
not in(select id_producto from productos order by id_producto limit 0) order by id_producto limit 6

select * from productos order by id_producto

SELECT vcl.id_valoracionescli,vcl.comentario,vcl.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,prd.nombre_producto,
val.valoraciones 
FROM valoraciones_clientes as vcl 
INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
INNER JOIN valoraciones	AS val ON vcl.fk_id_valoraciones = val.id_valoraciones 
WHERE vcl.fk_id_productos = 1
ORDER BY vcl.id_valoracionescli

select * from clientes

SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, usuario
FROM clientes WHERE id_cliente=1 ORDER BY id_cliente 

select * from pedidos_establecidos
delete from pedidos_establecidos where id_pedidos_establecidos=11;
select * from detallepedidos_establecidos

--Insertando datos en la columna de estados
select * from estados;

insert into estados(estado) values('Comprando'),('Activo'),('Bloqueado');

--Alterando la tabla de clientes para añadir la columna de estados
select * from clientes;

alter table clientes add column fk_id_estado int;--Añadiendo el campo 
alter table clientes alter column fk_id_estado set default 8;--Añadiendo el default a la tabla
alter table clientes add constraint clientes_fk foreign key (fk_id_estado) references estados(id_estados) on update cascade on delete cascade;

update clientes set fk_id_estado = 8--Actualizamos los registros que teniamos

--Hacemos inserción sin especificar el estado para demostrar que siempre sera 8 = Activo, a menos que este se cambie
INSERT INTO clientes (nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, 
telefono_cliente, direccion_cliente, usuario, contrasena)values
('Carlos', 'Fuentes', 'CarlosFfu@gmaisssl.com', '09452334-3', '7327-8323', 'DOMICILIO 
CONOCIDO', 'Fuentes682', '876120');

select * from admins
select * from clientes
select * from estados
update clientes set fk_id_estado=9 where id_cliente=10;
select * from productos where id_producto
not in(select id_producto from productos order by id_producto limit 4) order by id_producto limit 4;

SELECT clt.id_cliente,clt.nombre_cliente,clt.apellido_cliente,clt.correo_cliente,clt.dui_cliente,clt.telefono_cliente,
clt.direccion_cliente,clt.usuario,est.estado
FROM clientes as clt 
INNER JOIN estados AS est ON clt.fk_id_estado = est.id_estados
WHERE id_cliente
NOT IN(SELECT id_cliente FROM clientes ORDER BY id_cliente LIMIT 0) ORDER BY id_cliente limit 8

SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, usuario
            FROM clientes 
            WHERE id_cliente = 1
            ORDER BY id_cliente

select * from valoraciones_clientes order by id_valoracionescli

SELECT vcl.id_valoracionescli, vcl.comentario, clt.usuario, prd.nombre_producto, vcl.fk_id_valoraciones 
FROM valoraciones_clientes AS vcl
INNER JOIN clientes AS clt ON vcl.fk_id_cliente = clt.id_cliente 
INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto 
WHERE vcl.id_valoracionescli
NOT IN (SELECT id_valoracionescli FROM valoraciones_clientes ORDER BY id_valoracionescli LIMIT 0) ORDER BY vcl.id_valoracionescli limit 8

SELECT vcl.id_valoracionescli,vcl.comentario,vcl.fk_id_cliente,clt.nombre_cliente,clt.apellido_cliente,prd.nombre_producto,
                val.valoraciones 
                FROM valoraciones_clientes as vcl 
                INNER JOIN clientes AS clt ON  vcl.fk_id_cliente = clt.id_cliente
                INNER JOIN productos AS prd ON vcl.fk_id_productos = prd.id_producto
                INNER JOIN valoraciones	AS val ON vcl.fk_id_valoraciones = val.id_valoraciones 
                WHERE vcl.id_valoracionescli = 1
                ORDER BY vcl.id_valoracionescli

create table inventario(
	id_inventario serial not null,
	cantidada int not null,
	cantidadn int not null,
	modificado boolean not null,
	fecha date default current_date,
	fk_id_admin int not null,
	fk_id_producto int not null,
	constraint inventario_pk primary key (id_inventario),
	constraint inventario_admins_fk foreign key (fk_id_admin) references admins(id_admin) on update cascade on delete cascade,
	constraint inventario_productos_fk foreign key (fk_id_producto) references productos(id_producto) on update cascade on delete cascade
);

--Trigger para actualizar la cantidad del producto luego de insertar en la tabla de inventario
CREATE FUNCTION actualizarcantidadprdi() RETURNS TRIGGER
AS
$$
DECLARE 
--Declaramos las variables
idmax int:=(SELECT MAX(id_inventario) FROM inventario);
idprd int:=(SELECT fk_id_producto FROM inventario 
			   WHERE id_inventario=idmax);
cantprd int:=(SELECT cantidadn FROM inventario 
		  WHERE id_inventario = idmax);
BEGIN
--Indicamos el procedimiento a realizar
UPDATE productos SET cantidad=cantidad+cantprd 
WHERE id_producto=idprd;
RETURN NEW;
END
$$
LANGUAGE plpgsql;--Final del la función del trigger

--Inicio del trigger
CREATE TRIGGER tr_actualizarCPI AFTER INSERT ON inventario
FOR EACH ROW
EXECUTE PROCEDURE actualizarcantidadprdi();--Final del Trigger

select * from productos
select * from inventario
insert into inventario(cantidada,cantidadn,modificado,fk_id_admin,fk_id_producto) 
values(34,1,false,1,1);

--Funciono el trigger

SELECT inv.id_inventario, inv.cantidada, inv.cantidadn, inv.modificado, inv.fecha,  inv.fk_id_producto, adm.nombre_admin, adm.apellido_admin, prd.nombre_producto
FROM inventario AS inv
INNER JOIN admins AS adm ON inv.fk_id_admin = adm.id_admin
INNER JOIN productos AS prd ON inv.fk_id_producto = prd.id_producto
WHERE inv.id_inventario 
NOT IN (SELECT id_inventario FROM inventario ORDER BY id_inventario LIMIT 0) ORDER BY inv.id_inventario desc LIMIT 5;

select * from productos

update productos set descripcion_producto = 'hello kitty' where id_producto = 1

alter table productos add column descripcion_producto varchar(150);

