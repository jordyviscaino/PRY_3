<?php

    $host = "localhost";
    $user = "root";
    $pass = "36974125";
    $db = "pry_crud";
    $charset = "utf8mb4";
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try{
        $pdo = new PDO($dsn, $user, $pass);

        

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      
    }catch(\Throwable $th){
        echo "Error de conexion: " . $th->getMessage();
        exit;
    }

?>