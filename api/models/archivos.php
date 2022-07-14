<?php
/*
*	Clase para manejar la tabla Valoraciones de la base de datos.
*   Es clase hija de Validator.
*/

class Archivos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_archivo = null;
    private $nombre_archivo = null;
    private $fecha_subida = null;
    private $fk_id_folder = null;
    private $tamano = null;
    private $fk_id_estado = null;
    private $nombre_original = null;
    private $ruta ='../documents/archivosFolders/';

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */

    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_archivo = $value;
            return true;
        } else {
            return false;
        }
    }

    
    public function setNombreArch($file)
    {
        if ($this->validateFile($file)) {
            $this->nombre_archivo = $this->getFileName();
            return true;
        } else {
            return false;
        }
    }

    public function setFecha($value)
    {
        if ($this->validateDate($value)) {
            $this->fecha_subida = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdFolder($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_folder = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTamano($file)
    {
        $this->tamano = $this->validateSize($file);
        return true;
    }

    public function setOriginal($value)
    {
        if ($this->validateString($value, 1, 200)) {
            $this->nombre_original = $value;
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
        return $this->id_archivo;
    }

    public function getNombreArch()
    {
        return $this->nombre_archivo;
    }

    public function getFecha()
    {
        return $this->fecha_subida;
    }

    public function getIdFolder()
    {
        return $this->fk_id_folder;
    }

    public function getTamano()
    {
        return $this->tamano;
    }

    public function getIdEstado()
    {
        return $this->fk_id_estado;
    }

    public function getOriginal()
    {
        return $this->nombre_original;
    }

    public function getRoute()
    {
        return $this->ruta;
    }

    /*
    *   Metodos para consultas
    */
    //Función para consultar los archivos con limit
    public function obtenerArchivoLimit($limit)
    {
        $sql = 'SELECT id_archivo, nombre_archivo, fecha_subida, fk_id_folder, tamano, fk_id_estado, nombre_original
                FROM archivos
                WHERE fk_id_estado = 4 AND fk_id_folder = ? ORDER BY id_archivo DESC OFFSET ? LIMIT 5';
        $params = array($this->fk_id_folder,$limit);
        return Database::getRows($sql, $params);
    }
 
    //Buscar Archivo
    public function buscarArchivos($value)
    {
        $sql = 'SELECT id_archivo, nombre_archivo, fecha_subida, fk_id_folder, tamano, fk_id_estado, nombre_original
                FROM archivos 
                WHERE (CAST(fecha_subida as varchar) ILIKE ? OR tamano ILIKE ? OR nombre_original ILIKE ?)  
                AND fk_id_estado = 4 AND fk_id_folder = ?';
        $params = array("%$value%","%$value%","%$value%",$this->fk_id_folder);
        return Database::getRows($sql, $params);
    }

    //Buscar Archivo por el folder
    public function obtenerFolder()
    {
        $sql = 'SELECT id_archivo, nombre_archivo, fecha_subida, fk_id_folder, tamano, fk_id_estado, nombre_original
                FROM archivos
                WHERE id_archivo=? AND fk_id_estado = 4 AND fk_id_folder = ?';
        $params = array($this->id_archivo,$this->fk_id_folder);
        return Database::getRow($sql, $params); 
    }

    //Buscar el nombre del folder y el nombre de la empresa
    public function nombreEmpFol()
    {
        $sql = 'SELECT emp.nombre_empresa,fol.nombre_folder
                FROM folders as fol
                INNER JOIN empresas AS emp ON emp.id_empresa = fol.fk_id_empresa
                WHERE fol.id_folder = ?';
        $params = array($_SESSION['id_folder']);
        return Database::getRow($sql, $params); 
    }

    //Comprobar que exista un folder con el nombre actual (PARA CREAR)
    public function checkArchivo()
    {
        $sql = 'SELECT id_archivo FROM archivos WHERE nombre_original = ? AND fk_id_folder = ? AND fk_id_estado = 4';
        $params = array($this->nombre_original,$this->fk_id_folder);
        if ($data = Database::getRow($sql, $params)) {
            return false;
        } else {
            return true;
        }
    }

    //Comprobar que exista un folder con el nombre actual (PARA ACTUALIZAR)
    public function checkArchivoACT()
    {
        $sql = 'SELECT id_archivo FROM archivos WHERE nombre_original = ? AND fk_id_folder = ? AND fk_id_estado = 4 AND id_archivo != ?';
        $params = array($this->nombre_original,$this->fk_id_folder, $this->id_archivo);
        if ($data = Database::getRow($sql, $params)) {
            return false;
        } else {
            return true;
        }
    }

    //Comprobar que exista un folder con el nombre actual (PARA CREAR)
    public function obtenerArch()
    {
        $sql = 'SELECT id_archivo, nombre_archivo, fecha_subida, fk_id_folder, tamano, fk_id_estado, nombre_original
                FROM archivos
                WHERE fk_id_estado = 4 AND fk_id_folder = ? AND id_archivo = ?';
        $params = array($this->fk_id_folder,$this->id_archivo);
        return Database::getRow($sql, $params); 
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Crear Archivo.
    public function crearArchivo()
    {
        $sql = 'INSERT INTO archivos(nombre_archivo, fk_id_folder, tamano, nombre_original)
                VALUES (?, ?, ?,?)';
        $params = array($this->nombre_archivo, $this->fk_id_folder,$this->tamano, $this->nombre_original);
        return Database::executeRow($sql, $params);
    }

    //Actualizar Archivo
    public function actualizarArchivo($archivoactual, $tamanoactual, $fechaactual)
    {
        // Se verifica si existe una nueva imagen para borrar la actual, de lo contrario se mantiene la actual.
        ($this->nombre_archivo) ? $this->deleteFile($this->getRoute(), $archivoactual) : $this->nombre_archivo = $archivoactual;
        ($this->tamano) ? $this->tamano = $this->tamano : $this->tamano = $tamanoactual;
        ($this->fecha_subida) ? $this->fecha_subida = $this->fecha_subida : $this->fecha_subida = $fechaactual;
        
        $sql = 'UPDATE archivos
                SET nombre_archivo=?, fecha_subida=?, fk_id_folder=?, tamano=?, nombre_original=?
                WHERE id_archivo = ?';
        $params = array($this->nombre_archivo, $this->fecha_subida, $this->fk_id_folder, $this->tamano, $this->nombre_original, $this->id_archivo);
        return Database::executeRow($sql, $params);
    }

     //Eliminar Archivo
     public function eliminarArchivo()
     {
         $sql = 'DELETE FROM archivos WHERE id_archivo = ?';
         $params = array($this->id_archivo);
         return Database::executeRow($sql, $params);
     }

     public function cambiarEstadoArch()
     {
         $sql = 'UPDATE archivos SET fk_id_estado = 3 WHERE id_archivo = ? and fk_id_folder = ?';
         $params = array($this->id_archivo, $this->fk_id_folder);
         return Database::executeRow($sql, $params);
     }
}