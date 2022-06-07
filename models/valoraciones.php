<?php
/*
*   Clase para manejar la tabla categorias de la base de datos.
*   Es clase hija de Validator.
*/
class Valoraciones extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_valoraciones = null;//id valoraciones 
    private $valoraciones = null;// valoraciones de producto

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_valoraciones($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_valoraciones = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setValoraciones($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->valoraciones = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_valoraciones()
    {
        return $this->id_valoraciones;
    }

    public function getValoraciones()
    {
        return $this->valoraciones;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD
    */
    public function searchRowsV($value)
    {
        $sql = '';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function readAllV()
    {
        $sql = 'SELECT id_valoraciones, valoraciones
                FROM valoraciones
                ORDER BY valoraciones';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM valoraciones
                WHERE id_valoraciones = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
