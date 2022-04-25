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
        if ($this->validateAlphanumeric($value, 1,50)) {
            $this->usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setContrasena($value)
    {
        if ($this->validateAlphanumeric($value, 1,50)) {
            $this->contrasena = $value;
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
    public function checkAdmin($alias)
    {
        $sql = 'SELECT id_admin FROM admins WHERE usuario = ?';
        $params = array($alias);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['id_admin'];
            $this->alias = $alias;
            return true;
        } else {
            return false;
        }
    }

     //Comprobar la contraseña del admin
    public function checkContrasenaADM($contrasena)
    {
        $sql = 'SELECT contrasena FROM admins WHERE id_usuario = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($contrasena, $data['contrasena'])) {
            return true;
        } else {
            return false;
        }
    };
    //Cambiar contraseña del admin
    public function cambiarContrasenaADM()
    {
        $sql = 'UPDATE admins SET contrasena = ? WHERE id_admin = ?';
        $params = array($this->contrasena, $this->id);
        return Database::executeRow($sql, $params);
    };
    //obtener el perfil del admin
    public function obtenerPerfilADM($id)
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, usuario
                FROM admins
                WHERE id_admin = ?';
        $params = array($id);
        return Database::getRow($sql, $params);
    };
    //Editar o cambiar el perfil del admin
    public function editarPerfilADM($idA, $nombreA, $apellidoA, $usuA)
    {
        $sql = 'UPDATE admins
                SET nombre_admin = ?, apellido_admin = ?, usuario = ?
                WHERE id_admin = ?';
        $params = array($nombreA, $apellidoA, $usuA);
        return Database::executeRow($sql, $params);
    };


       /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar Admins
    public function buscarAdmins($value)
    {
        $sql = 'SELECT id_admin, nombre_admin, apellido_admin, usuario
                FROM admins
                WHERE apellido_admin ILIKE ? OR nombre_admin ILIKE ?
                ORDER BY id_cliente';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    };
    //Crear Admin
    public function crearAdmin()
    {
        $sql = 'INSERT INTO Admins(nombre_admin, apellido_admin, usuario, contrasena)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre_admin, $this->apellido_admin, $this->usuario, $this->contrasena);
        return Database::executeRow($sql, $params);
    }
    //Obtener admin
    public function obtenerAdmin()
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
        $params = array($this->id);
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
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
 