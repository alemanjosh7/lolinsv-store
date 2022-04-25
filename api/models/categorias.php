<?php
/*
*	Clase para manejar la tabla categorias de la base de datos.
*   Es clase hija de Validator.
*/
class Categorias extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_categoria = null;
    private $nombre_categoria = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_categoria = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setName($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->nombre_categoria = $value;
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
        return $this->id_categoria;
    }

    public function getNombre()
    {
        return $this->nombre_categoria;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchCategory($value)
    {
        $sql = '';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function createCategory()
    {
        $sql = 'INSERT INTO categorias(nombre_categoria)
                VALUES(?)';
        $params = array($this->nombre_categoria);
        return Database::executeRow($sql, $params);
    }

    public function readCategories()
    {
        $sql = 'SELECT id_categoria, nombre_categoria
                FROM categorias
                ORDER BY nombre_categoria';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readACategory()
    {
        $sql = 'SELECT id_categoria, nombre_categoria
                FROM categorias
                WHERE id_categoria = ?';
        $params = array($this->id_categoria);
        return Database::getRow($sql, $params);
    }

    public function updateCategory()
    {
        $sql = 'UPDATE categorias
                SET nombre_categoria = ?';
        $params = array($this->nombre_categoria);
        return Database::executeRow($sql, $params);
    }

    public function deleteCategory()
    {
        $sql = 'DELETE FROM categorias
                WHERE id_categoria = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
