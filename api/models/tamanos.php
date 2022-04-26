<?php
/*
*	Clase para manejar la tabla Valoraciones de la base de datos.
*   Es clase hija de Validator.
*/
class Tamanos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;//Id del tamaño
    private $tamano = null;//tamaño 
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

    public function setTamano($value)
    {
        if ($this->validateString($value, 1, 150)) {
            $this->tamano = $value;
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

    public function getTamano()
    {
        return $this->tamano;
    }


    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Buscar tamaños
    public function buscarTamano($value)
    {
        $sql = 'SELECT id_tamano,tamano
        FROM tamanos
        WHERE tamano = ?';
        $params = array ("%$value%");
        return Database::getRow($sql, $params);
    }

    //mostrar todas las columnas de tamaños

    public function mostrarTamanos()
    {
        $sql = 'SELECT id_tamanos, tamano
                FROM tamanos';
        $params = null;
        return Database::getRows($sql, $params);
    }

    //obtener columna de tamaño por id

    public function obtenerTamano()
    {
        $sql = 'SELECT id_tamanos, tamanos
                FROM tamanos
                WHERE id_tamanos = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //Crear columna de tamaño

    public function crearTamano()
    {
        $sql = 'INSERT INTO tamanos(tamano)
                VALUES(?)';
        $params = array($tamano);
        return Database::executeRow($sql, $params);
    }


    //Eliminar valoracion del tamaño

    public eliminarValoracionCli(){
        $sql = 'DELETE FROM tamanos
                WHERE id_tamanos = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
