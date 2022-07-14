<?php
/*
*   Clase para manejar la tabla estados de la base de datos.
*   Es clase hija de Validator.
*/
class Estados extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_estado = null;
    private $nombre_estado = null;
  
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombreEstado($value)
    {
        if ($this->validateString($value, 1, 100)) {
            $this->nombre_estado = $value;
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
        return $this->id_estado;
    }

    public function getNombreEstado()
    {
        return $this->nombre_estado;
    }


    /*
    *   Metodos para consultas
    */

    //NA

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    //Obtener estados 
    public function obtenerEstados()
    {
        $sql = 'SELECT id_estado, nombre_estado
                FROM estados
                ORDER BY nombre_estado';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Obtener estado especifico
    public function obtenerEstado()
    {
        $sql = 'SELECT id_estado, nombre_estado
                FROM estados
                WHERE id_estado = ?';
        $params = array($this->id_estado);
        return Database::getRow($sql, $params);
    }
    

    //Crear estado
    public function crearEstado()
    {
        $sql = 'INSERT INTO estados(nombre_estado)
                VALUES(?)';
        $params = array($this->nombre_estado);
        return Database::executeRow($sql, $params);
    }

    //Actualizar estado
        public function actualizarEstado()
    {
        $sql = 'UPDATE estados
                SET nombre_estado = ?';
        $params = array($this->nombre_estado);
        return Database::executeRow($sql, $params);
    }

    //Eliminar estado
    public function eliminarEstado()
    {
        $sql = 'DELETE FROM estados
                WHERE id_estados = ?';
        $params = array($this->id_estado);
        return Database::executeRow($sql, $params);
    }

}