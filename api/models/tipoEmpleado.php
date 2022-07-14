<?php
/*
*   Clase para manejar la tabla estados de la base de datos.
*   Es clase hija de Validator.
*/
class TipoEmpleado extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_tipo_empleado = null;
    private $tipo_empleado = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setIdTipoEmpleado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_tipo_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTipoEmpleado($value)
    {
        if ($this->validateString($value, 1, 100)) {
            $this->tipo_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getIdTipoEmpleado()
    {
        return $this->id_tipo_empleado;
    }

    public function getTipoEmpleado()
    {
        return $this->tipo_empleado;
    }


    /*
    *   Metodos para consultas
    */
    //Obtener todos los tipo
    public function obtenerTipoEmpleado()
    {
        $sql = 'SELECT * FROM tipo_empleado';
        $params = null;
        return Database::getRows($sql, $params);
    }
    //Obtener todos los tipos excepto admin
    public function obtenerTipoEmpleadoNAD()
    {
        $sql = 'SELECT * FROM tipo_empleado WHERE id_tipo_empleado !=4';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
