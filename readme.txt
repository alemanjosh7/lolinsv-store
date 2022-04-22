Demo desarrollada con las siguientes tecnologías y herramientas:
- XAMPP v8.0.0 (PHP 8.0.0 y Apache 2.4.46)
- PostgreSQL 14.2 con pgAdmin 4
- Materialize v1.0.0
- Vanilla JS

Paso 1. Crear una base de datos llamada coffeeshop y luego hacer la importación con el archivo database.sql

Paso 2. Modificar las credenciales necesarias para la conexión con la base de datos, ubicadas en api/helpers/database.php
    Servidor: localhost (127.0.0.1)
    Usuario: postgres
    Contraseña: postgres
    Puerto por defecto (5432)

Paso 3. Ingresar al sitio web que se desea visualizar.
    Inicio del sitio privado (al ingresar por primera vez se pedirá crear un usuario):
        localhost/lolinsv/views/dashboard/
    Inicio del sitio público:
        localhost/lolinsv/views/public

Para que funcione PostgreSQL con XAMPP, es necesario acceder a la dirección C:\xampp\php y hacer lo siguiente:
1. Ubicar y abrir el archvio php.ini
2. Buscar la línea ;extension=pdo_pgsql
3. Borrar el ; que esta al inicio de la línea
4. Guardar los cambios y cerrar el archivo
5. Reiniciar Apache