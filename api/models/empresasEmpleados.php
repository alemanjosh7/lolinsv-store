<?php
/*
*   Clase para manejar la tabla estados de la base de datos.
*   Es clase hija de Validator.
*/
class EmpresasEmpleados extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_empresas_empleados = null;
    private $fk_id_empleado = null;
    private $fk_id_empresa = null;
  
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setIdEmpresasEmpleados($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_empresas_empleados = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdEmpresaEmpleados($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_empleado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdEmpresaEmpresa($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->fk_id_empresa = $value;
            return true;
        } else {
            return false;
        }
    }


    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getIdEmpresasEmpleados()
    {
        return $this->id_empresas_empleados;
    }

    public function getIdEmpresaEmpleados()
    {
        return $this->fk_id_empleado;
    }

    public function getIdEmpresaEmpresa()
    {
        return $this->fk_id_empresa;
    }


    /*
    *   Metodos para consultas
    */

    
    //Función para consultar con limit
    public function obtenerEmpresasEmpleadosLimit($limit)
    {
        $sql = 'SELECT empEmp.id_empresas_empleado, empDo.nombre_empleado, epmSa.nombre_empresa
        FROM empresas_empleados AS empEmp
        INNER JOIN empleados AS empDo ON empEmp.fk_id_empleado = empDo.id_empleado
        INNER JOIN empresas AS epmSa ON empEmp.fk_id_empresa = epmSa.id_empresa
        WHERE empEmp.fk_id_empresa = 1 AND empEmp.id_empresas_empleado  NOT IN(SELECT id_empresas_empleado FROM empleados ORDER BY id_empresas_empleado DESC LIMIT ?) ORDER BY empEmp.id_empresas_empleado DESC LIMIT 12';
        $params = array($limit);
        return Database::getRows($sql, $params);
    }

    //Función para consultar empresas donde empleado trabaja 
    public function obtenerEmpresasEmpleados($limit)
    {
        $sql = 'SELECT empEmp.id_empresas_empleado, empDo.nombre_empleado, epmSa.nombre_empresa
                FROM empresas_empleados AS empEmp
                INNER JOIN empleados AS empDo ON empEmp.fk_id_empleado = empDo.id_empleado
                INNER JOIN empresas AS epmSa ON empEmp.fk_id_empresa = epmSa.id_empresa
                WHERE empEmp.fk_id_empleado=2 OFFSET ?';
        $params = array($_SESSION['id_usuario'], $limit);
        return Database::getRows($sql, $params);
    }


     //Función para consultar empleados de una empresa 
     public function obtenerEmpleadosEmpresa($limit)
     {
         $sql = 'SELECT empEmp.id_empresas_empleado, empDo.nombre_empleado, epmSa.nombre_empresa
                FROM empresas_empleados AS empEmp
                INNER JOIN empleados AS empDo ON empEmp.fk_id_empleado = empDo.id_empleado
                INNER JOIN empresas AS epmSa ON empEmp.fk_id_empresa = epmSa.id_empresa
                WHERE empEmp.fk_id_empresa=4 OFFSET ?';
         $params = array($_SESSION['id_usuario'], $limit);
         return Database::getRows($sql, $params);
     }

     

    //Obtener Empleado especifico de empresa especifica
    public function obtenerEmpleadoEmpresa()
    {
        $sql = 'SELECT empEmp.id_empresas_empleado, empDo.nombre_empleado, epmSa.nombre_empresa
                FROM empresas_empleados AS empEmp
                INNER JOIN empleados AS empDo ON empEmp.fk_id_empleado = empDo.id_empleado
                INNER JOIN empresas AS epmSa ON empEmp.fk_id_empresa = epmSa.id_empresa
                WHERE empEmp.fk_id_empresa=4 AND empEmp.fk_id_empleado=?';
        $params = array($this->id_empresa);
        return Database::getRow($sql, $params);
    }

    //Obtener un registro especifico
    public function obtenerRegistro()
    {
        $sql = 'SELECT empEmp.id_empresas_empleado, empDo.nombre_empleado, epmSa.nombre_empresa
                FROM empresas_empleados AS empEmp
                INNER JOIN empleados AS empDo ON empEmp.fk_id_empleado = empDo.id_empleado
                INNER JOIN empresas AS epmSa ON empEmp.fk_id_empresa = epmSa.id_empresa
                WHERE empEmp.id_empresas_empleado = ?';
        $params = array($this->id_empresas_empleados);
        return Database::getRow($sql, $params);
    }

    //Buscar una empresa especifica
    public function obtenerEmpresa($idempr)
    {
        $sql = 'SELECT emp.id_empresa, emp.nombre_cliente, emp.apellido_cliente, emp.nombre_empresa, emp.numero_empresacontc, emp.correo_empresacontc, emp.direccion_empresa, emp.nit_empresa, est.nombre_estado
        FROM empresas AS emp
        INNER JOIN estados AS est ON emp.fk_id_estado = est.id_estado
        WHERE emp.fk_id_estado = 4 AND emp.id_empresa = ?';
        $params = array($idempr);
        return Database::getRow($sql, $params);
    }

     //obtener empleado
     public function obtenerEmpleado($id)
     {
         $sql = 'select e.id_empleado, e.nombre_empleado, e.apellido_empleado, e.dui_empleado, e.telefono_empleadocontc, e.correo_empleadocontc, e.usuario_empleado, tp.tipo_empleado, e.fk_id_estado FROM empleados as e INNER JOIN tipo_empleado AS tp ON tp.id_tipo_empleado = e.fk_id_tipo_empleado WHERE e.id_empleado = ?';
         $params = array($id);
         return Database::getRow($sql, $params);
     }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Crear asignación  de empresa donde el empleado trabaja 
    public function crearEmpresaEmpleado()
    {
        $sql = 'INSERT INTO empresas_empleados(fk_id_empleado,fk_id_empresa) VALUES(?,?)';
        $params = array($this->fk_id_empleado, $this->fk_id_empresa);
        return Database::executeRow($sql, $params);
    }

    //Actualizar empresa donde el empleado trabaja 
    public function actualizarEmpresaEmpleado()
    {
        $sql = 'UPDATE empresas_empleados
                SET fk_id_empleado=?, fk_id_empresa=?
                WHERE id_empresas_empleado = ?';
        $params = array($this->fk_id_empleado, $this->fk_id_empresa, $this->id_empresas_empleado);
        return Database::executeRow($sql, $params);
    }
    //Cambiar Empresa donde el empleado trabaja  
    public function cambiarEmpresaEmpleado()
    {
        $sql = 'UPDATE empresas_empleados
                SET fk_id_empresa=3
                WHERE id_empresas_empleado = ?';
        $params = array($this->id_empresas_empleado);
        return Database::executeRow($sql, $params);
    }
    //Eliminar empleado de empresa 
    public function eliminarEmpresaEmpleado()
    {
        $sql = 'DELETE FROM empresas_empleados WHERE id_empresas_empleado = ?';
        $params = array($this->id_empresas_empleado);
        return Database::executeRow($sql, $params);
    }

    //Eliminar conexión del empleado con la empresa
    public function eliminarConexionEmpEmpr()
    {
        $sql = 'DELETE FROM empresas_empleados WHERE fk_id_empresa = ? AND fk_id_empleado = ?';
        $params = array($this->fk_id_empresa, $this->fk_id_empleado);
        return Database::executeRow($sql, $params);
    }
}







