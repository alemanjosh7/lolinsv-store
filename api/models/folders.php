<?php
/*
*   Clase para manejar la tabla productos de la base de datos.
*   Es clase hija de Validator.
*/
class Folders extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_folder = null;
    private $nombre_folder = null;
    private $fk_id_empresa = null;
    private $fk_id_estado = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_folder = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombreFol($value)
    {
        if ($this->validateString($value, 1, 100)) {
            $this->nombre_folder = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdEmpresa($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_empresa = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdEstado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_estado = $value;
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
        return $this->id_folder;
    }

    public function getNombreFol()
    {
        return $this->nombre_folder;
    }

    public function getIdEmpresa()
    {
        return $this->fk_id_empresa;
    }

    public function getIdEstado()
    {
        return $this->fk_id_estado;
    }

    /*
    *   Metodos para consultas
    */
    //Función para consultar las empresas con limit
    public function obtenerFoldersLimit($limit)
    {
        $sql = 'SELECT id_folder, nombre_folder, fk_id_empresa, fk_id_estado
                FROM folders
                WHERE fk_id_estado = 4 AND fk_id_empresa = ?  OFFSET ? LIMIT 6';
        $params = array($this->fk_id_empresa,$limit);
        return Database::getRows($sql, $params);
    }

    //Buscar empresas para el admin

    //obtener el perfil del admin
    public function buscarFolders($value,$limit)
    {
        $sql = 'SELECT id_folder, nombre_folder, fk_id_empresa, fk_id_estado
                FROM folders 
                WHERE (nombre_folder ILIKE ?) AND fk_id_estado = 4 AND fk_id_empresa = ?  OFFSET ? LIMIT 6';
        $params = array("%$value%",$this->fk_id_empresa, $limit);
        return Database::getRows($sql, $params);
    }
    //Buscar una empresa especifica
    public function obtenerFolder()
    {
        $sql = 'SELECT id_folder, nombre_folder, fk_id_empresa, fk_id_estado
                FROM folders
                WHERE id_folder=? AND fk_id_estado = 4 AND fk_id_empresa = ?';
        $params = array($this->id_folder,$this->fk_id_empresa);
        return Database::getRow($sql, $params); 
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Crear empresa
    public function crearFolder()
    {
        $sql = 'INSERT INTO folders(nombre_folder,fk_id_empresa)
                VALUES (?,?)';
        $params = array($this->nombre_folder, $this->fk_id_empresa);
        return Database::executeRow($sql, $params);
    }
    //Actualizar empresa
    public function actualizarFolder()
    {
        $sql = 'UPDATE folders
                SET nombre_folder=?
                WHERE id_folder = ?';
        $params = array($this->nombre_folder, $this->id_folder);
        return Database::executeRow($sql, $params);
    }
    //Cambiar estado de empresa (Eliminado)
    public function cambiarEstadoFol()
    {
        $sql = 'UPDATE folders
                SET fk_id_estado=3
                WHERE id_folder = ?';
        $params = array($this->id_folder);
        return Database::executeRow($sql, $params);
    }
    //Eliminar empresa NO SE USARA A MENOS QUE EL PROFESOR DIGA
    public function eliminarFolder()
    {
        $sql = 'DELETE FROM folders WHERE id_folder = ?';
        $params = array($this->id_folder);
        return Database::executeRow($sql, $params);
    }

    //Comprobar que exista un folder con el nombre actual (PARA CREAR)
    public function checkFolderName()
    {
        $sql = 'SELECT id_folder FROM folders WHERE nombre_folder = ? AND fk_id_empresa = ? AND fk_id_estado = 4';
        $params = array($this->nombre_folder,$this->fk_id_empresa);
        if ($data = Database::getRow($sql, $params)) {
            return false;
        } else {
            return true;
        }
    }
    //Comprobar que exista un folder con el nombre actual (PARA ACTUALIZAR)
    public function checkFolderNameAct()
    {
        $sql = 'SELECT id_folder FROM folders WHERE nombre_folder = ? AND fk_id_empresa = ? AND fk_id_estado = 4 AND id_folder !=?';
        $params = array($this->nombre_folder,$this->fk_id_empresa,$this->id_folder);
        if ($data = Database::getRow($sql, $params)) {
            return false;
        } else {
            return true;
        }
    }
}
