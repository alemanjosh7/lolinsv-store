<?php
/*
*	Clase para manejar la tabla productos de la base de datos.
*   Es clase hija de Validator.
*/
class Productos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id_producto = null;
    private $nombre_producto = null;
    private $imagen_producto = null;
    private $precio_producto = null;
    private $cantidad_producto = null;
    private $id_categoria = null;
    private $id_valoraciones = null;
    private $id_admin = null;
    private $ruta = '../images/productos/';

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setName($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->nombre_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setImage($file)
    {
        if ($this->validateImageFile($file, 500, 500)) {
            $this->imagen_producto = $this->getFileName();
            return true;
        } else {
            return false;
        }
    }

    public function setPrice($value)
    {
        if ($this->validateMoney($value)) {
            $this->precio_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setQuantity($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cantidad_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCategory($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_categoria = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setRating($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAdmin($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id_admin = $value;
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
        return $this->id_producto;
    }

    public function getName()
    {
        return $this->nombre_producto;
    }

    public function getPrice()
    {
        return $this->precio_producto;
    }

    public function getQuantity()
    {
        return $this->cantidad_producto;
    }

    public function getImage()
    {
        return $this->imagen_producto;
    }

    public function getCategory()
    {
        return $this->id_categoria;
    }

    public function getRating()
    {
        return $this->id_valoraciones;
    }

    public function getAdmin()
    {
        return $this->id_admin;
    }


    public function getRoute()
    {
        return $this->ruta;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    /*  
    *   Buscar productos por su nombre
    */
    public function searchProduct($nombre)
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.nombre_producto, p.precio_producto, p.cantidad, cate.nombre_categoria, val.valoraciones
                FROM productos AS p 
                INNER JOIN categorias AS cate ON cate.id_categoria = p.fk_id_categoria
                INNER JOIN valoraciones AS val ON val.id_valoraciones = p.fk_id_valoraciones
                WHERE nombre_producto ILIKE ?';
        $params = array("%$nombre%");
        return Database::getRows($sql, $params);
    }

    public function createProduct()
    {
        $sql = 'INSERT INTO productos(nombre_producto, imagen_producto, precio_producto, cantidad, fk_id_categoria, fk_id_valoraciones, fk_id_admin)
                VALUES(?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_producto, $this->imagen_producto, $this->precio_producto, $this->cantidad_producto, $this->id_categoria, $this->id_valoraciones, $this->id_admin);
        return Database::executeRow($sql, $params);
    }

    public function readAllProducts()
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.nombre_producto, p.precio_producto, p.cantidad, cate.nombre_categoria, val.valoraciones, p.imagen_producto
                FROM productos AS p 
                INNER JOIN categorias AS cate ON cate.id_categoria = p.fk_id_categoria
                INNER JOIN valoraciones AS val ON val.id_valoraciones = p.fk_id_valoraciones
                ORDER BY nombre_producto	
                ';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readOneProduct()
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.nombre_producto, p.precio_producto, p.cantidad, p.nombre_categoria, cate.nombre_categoria, val.valoraciones
                FROM productos AS p 
                INNER JOIN categorias AS cat ON cate.id_categoria = p.fk_id_categoria
                INNER JOIN valoraciones AS val ON val.id_valoraciones = p.fk_id_valoraciones
                WHERE id_producto ILIKE ? 
                ORDER BY nombre_producto';
        $params = array($this->id_producto);
        return Database::getRow($sql, $params);
    }

    public function updateProduct($current_image)
    {
        // Se verifica si existe una nueva imagen para borrar la actual, de lo contrario se mantiene la actual.
        ($this->imagen_producto) ? $this->deleteFile($this->getRoute(), $current_image) : $this->imagen_producto = $current_image;

        $sql = 'UPDATE productos
                SET imagen_producto = ?, nombre_producto = ?, precio_producto = ?, cantidad = ?, fk_id_categoria = ?
                fk_id_valoraciones = ?, fk_id_admin = ?, WHERE id_producto = ?';
        $params = array($this->imagen_producto, $this->nombre_producto, $this->precio_producto, $this->cantidad_producto, $this->id_categoria, $this->id_valoraciones, $this->id_admin, $this->id_producto);
        return Database::executeRow($sql, $params);
    }

    public function updateProductPrice()
    {
        $sql = 'update productos set precio_producto = ? where id_producto = ?';
        $params = array($this->precio_producto, $this->id_producto);
        return Database::executeRow($sql, $params);
    }


    public function deleteProduct()
    {

        $sql = 'DELETE FROM productos
                WHERE id_producto = ?';
        $params = array($this->id_producto);
        return Database::executeRow($sql, $params);
    }
}
