<?php
namespace App\Lib;

use PDO;

class Database
{
    public static function StartUp()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=apimascotasdb;charset=utf8', 'root', '');

        /*$pdo = new PDO('mysql:host=localhost;dbname=dinbeat7_apiMascota;charset=utf8', 'dinbeat7_api', '0.t0?q;xlq-}');*/

        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        
        return $pdo;
    }
}