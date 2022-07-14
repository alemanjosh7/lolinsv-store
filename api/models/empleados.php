<?php
/*
*	Clase para manejar la tabla usuarios de la base de datos.
*   Es clase hija de Validator.
*/
class Empleados extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_empleado = null; //id de empleado
    private $nombre_empleado = null; //nombre del empleado
    private $apellido_empleado = null; //apellido del empleado
    private $dui_empleado = null; //dui del empleado
    private $telefono_empleadocontc = null; //telefono del empleado
    private $correo_empleadocontc = null; //correo del empleado
    private $usuario_empleado = null; //usuario del empleado
    private $contrasena_empleado = null; //contraseña del empleado
    private $id_tipo_empleado = null; //tipo empleado
    private $id_estado = null; //estado del empleado

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre($value)
    {
        if ($this->validateAlphabetic($value, 1, 100)) {
            $this->nombre_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellido($value)
    {
        if ($this->validateAlphabetic($value, 1, 100)) {
            $this->apellido_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDUI($value)
    {
        if ($this->validateDUI($value)) {
            $this->dui_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefono($value)
    {
        if ($this->validatePhone($value)) {
            $this->telefono_empleadocontc = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreo($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo_empleadocontc = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setUsuario($value)
    {
        if ($this->validateAlphanumeric($value, 1, 100)) {
            $this->usuario_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setContrasena($value)
    {
        if ($this->validatePassword($value)) {
            $this->contrasena_empleado = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setTipoEmpleado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_tipo_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        if ($this->validateBoolean($value)) {
            $this->id_estado = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id_empleado;
    }

    public function getNombre()
    {
        return $this->nombre_empleado;
    }

    public function getApellido()
    {
        return $this->apellido_empleado;
    }

    public function getNombreCl()
    {
        $ncl = $this->nombre_empleado . " " . $this->apellido_empleado;
        return $ncl;
    }

    public function getDUI()
    {
        return $this->dui_empleado;
    }

    public function getTelefono()
    {
        return $this->telefono_empleadocontc;
    }

    public function getCorreo()
    {
        return $this->correo_empleadocontc;
    }

    public function getUsuario()
    {
        return $this->usuario_empleado;
    }

    public function getContrasena()
    {
        return $this->contrasena_empleado;
    }

    public function getTipoEmpleado()
    {
        return $this->id_tipo_empleado;
    }

    public function getEstado()
    {
        return $this->id_estado;
    }
    /*
    *   Métodos para gestionar la cuenta del empleado.
    */
    //Comprobar que el empleado exista
    public function checkUsuarioEmpleado($usuario)
    {
        $sql = 'SELECT id_empleado FROM empleados WHERE usuario_empleado = ?';
        $params = array($usuario);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_empleado = $data['id_empleado'];
            $this->usuario_empleado = $usuario;
            return true;
        } else {
            return false;
        }
    }
    //Comprobar que el empleado exista y no este bloqueado
    public function checkEmpleadosActivos()
    {
        $sql = 'SELECT id_empleado FROM empleados WHERE id_empleado = ? AND fk_id_estado = 4';
        $params = array($this->id_empleado);
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }
    //Comprobar la contraseña del empleado
    public function checkContrasenaEmpleado($contrasena)
    {
        $sql = 'SELECT contrasena_empleado FROM empleados WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($contrasena, $data['contrasena_empleado'])) {
            return true;
        } else {
            return false;
        }
    }
    //obtener empleado
    public function obtenerEmpleado($id)
    {
        $sql = 'select e.id_empleado, e.nombre_empleado, e.apellido_empleado, e.dui_empleado, e.telefono_empleadocontc, e.correo_empleadocontc, e.usuario_empleado, tp.tipo_empleado, e.fk_id_estado, e.fk_id_tipo_empleado FROM empleados as e INNER JOIN tipo_empleado AS tp ON tp.id_tipo_empleado = e.fk_id_tipo_empleado WHERE e.id_empleado = ?';
        $params = array($id);
        return Database::getRow($sql, $params);
    }


    //Colocamos las variables de sesión del nombre del usuario y su apellido
    public function nombreApellidoEmpleado()
    {
        $sql = 'SELECT nombre_empleado, apellido_empleado FROM empleados WHERE id_empleado = ?';
        $params = array($_SESSION['id_usuario']);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['nombreUsuario'] = $data['nombre_empleado'];
            $_SESSION['apellidoUsuario'] = $data['apellido_empleado'];
            return true;
        } else {
            return false;
        }
    }

    //Colocamos las variables de sesión del tipo de empleado
    public function tipoEmpleado()
    {
        $sql = 'SELECT fk_id_tipo_empleado FROM empleados WHERE id_empleado = ?';
        $params = array($_SESSION['id_usuario']);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['tipo_usuario'] = $data['fk_id_tipo_empleado'];
            return true;
        } else {
            return false;
        }
    }
    //Cambiar contraseña del empleado
    public function cambiarContrasenaEmpleado()
    {
        $sql = 'UPDATE empleados SET contrasena_empleado = ? WHERE id_empleado = ?';
        $params = array($this->contrasena_empleado, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }
    //obtenemos la contraseña
    public function obtenerContra($id)
    {
        $sql = 'SELECT contrasena_empleado FROM empleados WHERE id_empleado = ?';
        $params = array($id);
        if ($data = Database::getRow($sql, $params)) {
            $this->contrasena_empleado = $data['contrasena_empleado'];
            return $data;
        } else {
            return false;
        }
    }
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar empleados
    public function buscarEmpleados($value)
    {
        $sql = 'select e.id_empleado, e.nombre_empleado, e.apellido_empleado, e.dui_empleado, e.telefono_empleadocontc, e.correo_empleadocontc, e.usuario_empleado, tp.tipo_empleado, e.fk_id_estado FROM empleados as e INNER JOIN tipo_empleado AS tp ON tp.id_tipo_empleado = e.fk_id_tipo_empleado WHERE e.nombre_empleado ILIKE ? OR e.apellido_empleado ILIKE ? OR e.dui_empleado ILIKE ? OR e.telefono_empleadocontc ILIKE ? OR e.correo_empleadocontc ILIKE ? ORDER BY e.id_empleado';
        $params = array("%$value%", "%$value%", "%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
    //Buscar empleados
    public function buscarEmpleadosLimit2($value,$limit)
    {
        $sql = 'select e.id_empleado, e.nombre_empleado, e.apellido_empleado, e.dui_empleado, e.telefono_empleadocontc, e.correo_empleadocontc, e.usuario_empleado, tp.tipo_empleado, e.fk_id_estado FROM empleados as e INNER JOIN tipo_empleado AS tp ON tp.id_tipo_empleado = e.fk_id_tipo_empleado WHERE e.nombre_empleado ILIKE ? OR e.apellido_empleado ILIKE ? OR e.dui_empleado ILIKE ? OR e.telefono_empleadocontc ILIKE ? OR e.correo_empleadocontc ILIKE ? ORDER BY e.id_empleado LIMIT ?';
        $params = array("%$value%", "%$value%", "%$value%", "%$value%", "%$value%",$limit);
        return Database::getRows($sql, $params);
    }
    //Crear empleado
    public function crearEmpleado()
    {
        $sql = 'INSERT INTO empleados (nombre_empleado,apellido_empleado,dui_empleado,telefono_empleadocontc,correo_empleadocontc,usuario_empleado,contrasena_empleado,fk_id_tipo_empleado) VALUES (?,?,?,?,?,?,?,?)';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui_empleado, $this->telefono_empleadocontc, $this->correo_empleadocontc, $this->usuario_empleado, $this->contrasena_empleado, $this->id_tipo_empleado);
        return Database::executeRow($sql, $params);
    }
    //Actualizar empleado
    public function actualizarEmpleado()
    {
        if($_SESSION['tipo_usuario'] == 4 && $_SESSION['id_usuario'] == $this->id_empleado){
            $sql = 'UPDATE empleados
                SET nombre_empleado =?,apellido_empleado=?,dui_empleado=?,telefono_empleadocontc=?,correo_empleadocontc=?,usuario_empleado=?,contrasena_empleado=?
                WHERE id_empleado = ?';
            $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui_empleado, $this->telefono_empleadocontc, $this->correo_empleadocontc, $this->usuario_empleado, $this->contrasena_empleado, $this->id_empleado);    
        }else{
            $sql = 'UPDATE empleados
                SET nombre_empleado =?,apellido_empleado=?,dui_empleado=?,telefono_empleadocontc=?,correo_empleadocontc=?,usuario_empleado=?,contrasena_empleado=?,fk_id_tipo_empleado=?, fk_id_estado=?
                WHERE id_empleado = ?';
            $params = array($this->nombre_empleado, $this->apellido_empleado, $this->dui_empleado, $this->telefono_empleadocontc, $this->correo_empleadocontc, $this->usuario_empleado, $this->contrasena_empleado, $this->id_tipo_empleado, $this->id_estado, $this->id_empleado);

        }
        return Database::executeRow($sql, $params);
    }
    //Eliminar empleado
    public function eliminarEmpleadoT()
    {
        $sql = 'DELETE FROM empleados
                WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::executeRow($sql, $params);
    }
    //Eliminar empleado cambiando su estado y manteniendo su registro
    public function eliminarEmpleado()
    {
        $sql = 'UPDATE empleados SET fk_id_estado = 3 WHERE id_empleado = ?';
        $params = array($this->id_empleado);
        return Database::executeRow($sql, $params);
    }
    //Buscar empleados por limite
    public function buscarEmpleadosLimite($limit)
    {
        $sql = 'select e.id_empleado, e.nombre_empleado, e.apellido_empleado, e.dui_empleado, e.telefono_empleadocontc, e.correo_empleadocontc, e.usuario_empleado, tp.tipo_empleado, e.fk_id_tipo_empleado, e.fk_id_estado 
                FROM empleados as e INNER JOIN tipo_empleado AS tp ON tp.id_tipo_empleado = e.fk_id_tipo_empleado 
                WHERE e.fk_id_estado != 3  order by id_empleado OFFSET ?  limit 6';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }
    //Obtener el perfil del empleado
    public function obtenerPerfilEmpleado()
    {
        $sql = 'SELECT id_empleado, nombre_empleado, apellido_empleado, correo_empleadocontc, dui_empleado, telefono_empleadocontc, usuario_empleado
                FROM empleados
                WHERE id_empleado = ?';
         $params = array($_SESSION['id_usuario']);
         return Database::getRows($sql, $params);
    }
    //Obtener el tipo de empleado
    public function obtenerTipoEmpleado()
    {
        $sql = 'select tp.tipo_empleado, e.fk_id_tipo_empleado FROM empleados as e INNER JOIN tipo_empleado AS tp ON tp.id_tipo_empleado = e.fk_id_tipo_empleado';
        $params = null;
        return Database::getRows($sql, $params);
    }
    //Actualizar el perfil
    public function actualizarPerfil()
    {   
        $sql = 'UPDATE empleados SET nombre_empleado = ?, apellido_empleado = ?, correo_empleadocontc = ?, dui_empleado = ?, telefono_empleadocontc = ?, usuario_empleado = ? WHERE id_empleado = ?';
        $params = array($this->nombre_empleado, $this->apellido_empleado, $this->correo_empleadocontc, $this->dui_empleado, $this->telefono_empleadocontc, $this->usuario_empleado, $this->id_empleado);
        return Database::executeRow($sql, $params);
    }
}
