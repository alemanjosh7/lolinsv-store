<?php
/*
*	Clase para manejar la tabla usuarios de la base de datos.
*   Es clase hija de Validator.
*/
class Clientes extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null; //id de cliente
    private $nombre = null; //nombre del cliente
    private $apellido = null; //apellido del cliente
    private $correo = null; //correo del cliente
    private $dui = null; //dui del cliente
    private $telefono = null; //telefono del cliente
    private $direccion = null; //direccion del cliente
    private $usuario = null; //usuario del cliente
    private $contrasena = null; //contraseña del cliente
    private $idestado = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre($value)
    {
        if ($this->validateAlphabetic($value, 1, 100)) {
            $this->nombre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellido($value)
    {
        if ($this->validateAlphabetic($value, 1, 100)) {
            $this->apellido = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreo($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDUI($value)
    {
        if ($this->validateDUI($value)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefono($value)
    {
        if ($this->validatePhone($value)) {
            $this->telefono = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDireccion($value)
    {
        if ($this->validateString($value, 1, 500)) {
            $this->direccion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setUsuario($value)
    {
        if ($this->validateAlphanumeric($value, 1, 100)) {
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

    public function setEstado($value)
    {
        if ($this->validateBoolean($value)) {
            $this->idestado = $value;
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
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getNombreCl()
    {
        $ncl = $this->nombre . " " . $this->apellido;
        return $ncl;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getDUI()
    {
        return $this->dui;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }

    public function getEstado()
    {
        return $this->idestado;
    }

    /*
    *   Métodos para gestionar la cuenta del usuario.
    */
    //Comprobar que el usuario exista
    public function checkUsuarioCl($usuario)
    {
        $sql = 'SELECT id_cliente FROM clientes WHERE usuario = ?';
        $params = array($usuario);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['id_cliente'];
            $this->usuario = $usuario;
            return true;
        } else {
            return false;
        }
    }
    //Comprobar que el usuario exista y no este bloqueado en el 
    public function checkUsuarioClLog()
    {
        $sql = 'SELECT usuario FROM clientes WHERE id_cliente = ? AND fk_id_estado = 8';
        $params = array($this->id);
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }
    //Comprobar la contraseña del usuario
    public function checkContrasenaCl($contrasena)
    {
        $sql = 'SELECT contrasena FROM clientes WHERE id_cliente = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($contrasena, $data['contrasena'])) {
            return true;
        } else {
            return false;
        }
    }
    //obtener el perfil del cliente
    public function obtenerPerfilCl($id)
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion, usuario
                FROM clientes
                WHERE id_cliente = ?';
        $params = array($id);
        return Database::getRow($sql, $params);
    }
    //Editar o cambiar el perfil del cliente
    public function editarPerfilCl($idu, $nombre, $apellido, $correo, $DUI, $tel, $direc, $usu)
    {
        $sql = 'UPDATE clientes
                SET nombre_cliente = ?, apellido_cliente = ?, correo_cliente = ?, dui_cliente = ?, telefono_cliente = ?, 
                direccion_cliente = ?, usuario = ?
                WHERE id_cliente = ?';
        $params = array($nombre, $apellido, $correo, $DUI, $tel, $direc, $idu);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar Clientes
    public function buscarClientes($value)
    {
        $sql = 'SELECT clt.id_cliente,clt.nombre_cliente,clt.apellido_cliente,clt.correo_cliente,clt.dui_cliente,clt.telefono_cliente,
                clt.direccion_cliente,clt.usuario,est.estado
                FROM clientes as clt 
                INNER JOIN estados AS est ON clt.fk_id_estado = est.id_estados
                WHERE apellido_cliente ILIKE ? OR nombre_cliente ILIKE ? OR usuario ILIKE ? OR telefono_cliente ILIKE ? OR correo_cliente ILIKE ?
                ORDER BY id_cliente';
        $params = array("%$value%", "%$value%", "%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
    //Crear cliente
    public function crearCliente()
    {
        $sql = 'INSERT INTO clientes(nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, 
                telefono_cliente , direccion_cliente, usuario, contrasena, fk_id_estado)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, 8)';
        $params = array($this->nombre,$this->apellido,$this->correo,$this->dui,$this->telefono,$this->direccion,$this->usuario,$this->contrasena);
        return Database::executeRow($sql, $params);
    }
    //Obtener clientes
    public function obtenerClientes()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, usuario
                FROM clientes 
                ORDER BY id_cliente';
        $params = null;
        return Database::getRows($sql, $params);
    }
    //Obntener un cliente especifico
    public function obtenerCliente()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, usuario, fk_id_estado
            FROM clientes 
            WHERE id_cliente = ?
            ORDER BY id_cliente';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    //Actualizar Cliente
    public function actualizarCliente()
    {
        $sql = 'UPDATE clientes
                SET nombre_cliente = ?, apellido_cliente = ?, correo_cliente = ?, dui_cliente = ?, telefono_cliente = ?, 
                direccion_cliente = ?, usuario = ?, fk_id_estado = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->dui, $this->telefono, $this->direccion, $this->usuario, $this->idestado, $this->id);
        return Database::executeRow($sql, $params);
    }
    //Eliminar Cliente
    public function eliminarCliente()
    {
        $sql = 'DELETE FROM clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
    //Buscar Clientes por limite
    public function obtenerClientesLimit($limit)
    {
        $sql = 'SELECT clt.id_cliente,clt.nombre_cliente,clt.apellido_cliente,clt.correo_cliente,clt.dui_cliente,clt.telefono_cliente,
            clt.direccion_cliente,clt.usuario,est.estado
            FROM clientes as clt 
            INNER JOIN estados AS est ON clt.fk_id_estado = est.id_estados
            WHERE id_cliente
            NOT IN(SELECT id_cliente FROM clientes ORDER BY id_cliente LIMIT ?) ORDER BY id_cliente limit 1';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }

    //Colocamos las variables de sesión del nombre del usuario y su apellido
    public function nombreApellidoAdminCl()
    {
        $sql = 'SELECT nombre_cliente, apellido_cliente FROM clientes WHERE id_cliente= ?';
        $params = array($_SESSION['id_cliente']);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['nombreUsuario'] = $data['nombre_cliente'];
            $_SESSION['apellidoUsuario'] = $data['apellido_cliente'];
            return true;
        } else {
            return false;
        }
    }

    //Cambiar contraseña del cliente
    public function cambiarContrasenaCl()
    {
        $sql = 'UPDATE clientes SET contrasena = ? WHERE id_cliente = ?';
        $params = array($this->contrasena, $this->id);
        $data = Database::executeRow($sql, $params);
        return true;
    }
    //Comprobar que halla un pedido aun en proceso de compra en la bd
    public function checkPedidoCl()
    {
        $sql = 'SELECT * FROM pedidos_establecidos WHERE fk_id_estado = 7 AND fk_id_cliente = ?';
        $params = array($_SESSION['id_cliente']);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['id_pedidoEsta'] = $data['id_pedidos_establecidos'];
            return true;
        } else {
            return false;
        }
    }
    //Metodo para obtener el pedido del cliente
    //Comprobar que halla un pedido aun en proceso de compra en la bd
    public function obtenerCorreoCl()
    {
        $sql = 'SELECT correo_cliente,usuario,nombre_cliente,apellido_cliente FROM clientes WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}
