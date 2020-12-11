<?php

class DBFactory
{
    public static function getMysqlConnexionWithPDO()
    {
        return new PDO('mysql:host=localhost;dbname=openclassroom_practice',
            'root', 'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public static function createTableIfNotExistsWithPDO()
    {
        $pdo = self::getMysqlConnexionWithPDO();
        $query = $pdo->query('CREATE TABLE IF NOT EXISTS news (
          id int(10) unsigned NOT NULL AUTO_INCREMENT,
          author varchar(30) NOT NULL,
          title varchar(100) NOT NULL,
          content text NOT NULL,
          createdAt datetime NOT NULL,
          ModifiedAt datetime NOT NULL,
          PRIMARY KEY (id)) DEFAULT CHARSET=utf8;');
    }

//    public static function getMysqlConnexionWithMySQLi()
//    {
//        return new MySQLi('localhost', 'root', 'root', 'openclassroom_pratice');
//    }
}