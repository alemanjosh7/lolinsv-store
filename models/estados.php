<?php
/*
*	Clase para manejar la tabla categorias de la base de datos.
*   Es clase hija de Validator.
*/
class Estados extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $estado = null;

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

    public function setName($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->estado = $value;
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
        return $this->estado;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchState($value)
    {
        $sql = '';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function createState()
    {
        $sql = 'INSERT INTO estados(estado)
                VALUES(?)';
        $params = array($this->estado);
        return Database::executeRow($sql, $params);
    }

    public function readStates()
    {
        $sql = 'SELECT id_estados, estado
                FROM estados
                ORDER BY estado';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readAState()
    {
        $sql = 'SELECT id_estados, estado
                FROM estados
                WHERE id_estados = ?';
        $params = array($this->id_estado);
        return Database::getRow($sql, $params);
    }

    public function updateCategory()
    {
        $sql = 'UPDATE estados
                SET estado = ?';
        $params = array($this->estado);
        return Database::executeRow($sql, $params);
    }

    public function deleteCategory()
    {
        $sql = 'DELETE FROM estados
                WHERE id_estados = ?';
        $params = array($this->id_estado);
        return Database::executeRow($sql, $params);
    }
}
