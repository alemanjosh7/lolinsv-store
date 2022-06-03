<?php
/*
*   Clase para manejar la tabla productos de la base de datos.
*   Es clase hija de Validator.
*/
class Admins extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_admin = null;
    private $nombre_admin = null;
    private $apellido_admin = null;
    private $usuario = null;
    private $contrasena = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_admin($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_admin = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre_admin($value)
    {
        if ($this->validateString($value, 1, 50)) {
            $this->nombre_admin = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellido_admin($value)
    {
        if ($this->validateString($value, 1, 50)) {
            $this->apellido_admin = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setUsuario($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setContrasena($value)
    {
        if ($this->validatePassword($value)) {
            $this->contrasena = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_admin()
    {
        return $this->id_admin;
    }

    public function getNombre_admin()
    {
        return $this->nombre_admin;
    }

    public function getApellido_admin()
    {
        return $this->apellido_admin;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }


    /*
    *   Métodos para gestionar la cuenta del admin
    */
    //Función para validar que exista un usuario
    public function checkAdmin($alias)
    {
        $sql = 'SELECT id_admin FROM admins WHERE usuario = ?';
        $params = array($alias);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_admin = $data['id_admin'];
            $this->usuario = $alias;
            return true;
        } else {
            return false;
        }
    }
    //Función para validar que el usuario existente este activo
    public function checkAdminLog()
    {
        $sql = 'SELECT usuario FROM admins WHERE id_admin = ? AND fk_id_estado = 8';
        $params = array($this->id_admin);
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }

    //Comprobar la contraseña del admin
    public function checkContrasenaADM($contrasena)
    {
        $sql = 'SELECT contrasena FROM admins WHERE id_admin = ?';
        $params = array($this->id_admin);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($contrasena, $data['contrasena'])) {
            return true;
        } else {
            return false;
        }
    }
    //Cambiar contraseña del admin
    public function cambiarContrasenaADM()
    {
        $sql = 'UPDATE admins SET contrasena = ? WHERE id_admin = ?';
        $params = array($this->contrasena, $this->id_admin);
        return Database::executeRow($sql, $params);
    }
    //obtener el perfil del admin
    public function obtenerPerfilADM($id)
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, usuario
                FROM admins
                WHERE id_admin = ?';
        $params = array($id);
        return Database::getRow($sql, $params);
    }
    //Editar o cambiar el perfil del admin
    public function editarPerfilADM($idA, $nombreA, $apellidoA, $usuA)
    {
        $sql = 'UPDATE admins
                SET nombre_admin = ?, apellido_admin = ?, usuario = ?
                WHERE id_admin = ?';
        $params = array($nombreA, $apellidoA, $usuA);
        return Database::executeRow($sql, $params);
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar Admins
    //Buscar Admins
    public function buscarAdmins($value)
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, usuario
                FROM admins
                WHERE apellido_admin ILIKE ? OR nombre_admin ILIKE ? OR usuario ILIKE ?
                ORDER BY id_admin';
        $params = array("%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
    //Crear Admin
    public function crearAdmin()
    {
        $sql = 'INSERT INTO admins(nombre_admin, apellido_admin, usuario, contrasena)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre_admin, $this->apellido_admin, $this->usuario, $this->contrasena);
        return Database::executeRow($sql, $params);
    }
    //Obtener admin
    public function obtenerAdmins()
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, usuario
                FROM admins 
                ORDER BY id_admin';
        $params = null;
        return Database::getRows($sql, $params);
    }
    //Obntener un admine en especifico
    public function obtenerAdmin()
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, usuario
                FROM admins 
                WHERE id_admin = ?
                ORDER BY id_admin';
        $params = array($this->id_admin);
        return Database::getRow($sql, $params);
    }
    //Actualizar Admin
    public function actualizarAdmin()
    {
        $sql = 'UPDATE admins
                SET nombre_admin = ?, apellido_admin = ?, usuario = ?
                WHERE id_admin = ?';
        $params = array($this->nombre_admin, $this->apellido_admin, $this->usuario, $this->id_admin);
        return Database::executeRow($sql, $params);
    }
    //Eliminar Admin
    public function eliminarAdmin()
    {
        $sql = 'DELETE FROM admins
                WHERE id_admin = ?';
        $params = array($this->id_admin);
        return Database::executeRow($sql, $params);
    }
    //Colocamos las variables de sesión del nombre del usuario y su apellido
    public function nombreApellidoAdminL()
    {
        $sql = 'SELECT nombre_admin, apellido_admin FROM admins WHERE id_admin= ?';
        $params = array($_SESSION['id_usuario']);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['nombreUsuario'] = $data['nombre_admin'];
            $_SESSION['apellidoUsuario'] = $data['apellido_admin'];
            return true;
        } else {
            return false;
        }
    }
    //Obtener el perfil
    public function getProfile()
    {
        $sql = 'SELECT nombre_admin, apellido_admin, usuario
                FROM admins
                WHERE id_admin = ?';
        $params = array($_SESSION['id_usuario']);
        return Database::getRows($sql, $params);
    }
    //Actualizar la contraseña
    public function updateProfile()
    {
        $sql = 'UPDATE admins
                SET nombre_admin = ?, apellido_admin = ?, usuario = ?
                WHERE id_admin = ?';
        $params = array($this->nombre_admin, $this->apellido_admin, $this->usuario, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }
    //Obtener admins limit
    public function obtenerAdminsLimit($limit)
    {
        $sql = 'SELECT adm.id_admin, adm.nombre_admin, adm.apellido_admin, adm.usuario, adm.contrasena
            FROM admins as adm 
            WHERE fk_id_estado = 8 AND adm.id_admin NOT IN (select id_admin from admins order by id_admin limit ?) order by adm.id_admin DESC limit 12';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }
    //Eliminar administrador teoricamente cambiando su estado
    public function cambiarEstadoAdm()
    {
        $sql = 'UPDATE admins SET fk_id_estado = 10
                WHERE id_admin = ?';
        $params = array($this->id_admin);
        return Database::executeRow($sql, $params);
    }
}
