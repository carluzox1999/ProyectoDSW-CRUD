<?php
    // PDO

    class Conexion{

        private static $host = "localhost";
        private static $db = "proyecto";
        private static $user = "carlos";
        private static $pass = "carlos1234";
        // private static $dsn = "mysql:host=$host;dbname=$db;";
        private static $conProyecto = null;
        // $dsn = "pgsql:host=$host;dbname=$db;";

        public static function conectar(){
            if (null == self::$conProyecto) {
                try {
                    $dsn = "mysql:host=".self::$host.";dbname=".self::$db.";";
                    self::$conProyecto = new PDO($dsn, self::$user, self::$pass);
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            }
            return self::$conProyecto;
        }

        public static function desconectar(){
            self::$conProyecto = null;
        }
        
        
    }
