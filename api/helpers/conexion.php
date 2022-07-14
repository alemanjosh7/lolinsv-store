<?php
    class Conexion{
       static function ConectarDB(){
            $host = "localhost";
            $dbname = "";
            $username = "postgres";
            $password = "";
            $conn = null;
            try{
                $conn = new PDO("pgsql:host=$host; dbname=$dbname", $username, $password);
            }
            catch(Exception $e){
            }
            return $conn;
        }
    }
?>