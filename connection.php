<?php

class Database{
    public static $connection;

    public static function setUpConnect(){
        if(!isset(Database::$connection)){
            Database::$connection = new mysqli("localhost","root","VishuTharu@0918","lagatama_craft","3306");
        }
    }

    public static function iud($q){
        Database::setUpConnect();
        Database::$connection->query($q);
    }

    public static function search($q){
        Database::setUpConnect();
        $resultset = Database::$connection->query($q);
        return $resultset;
    }
}

?>