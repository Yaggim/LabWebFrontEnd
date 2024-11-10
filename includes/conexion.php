<?php

if (!isset($conn)) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "hardtech2";

    try {
        $conn = new PDO("mysql:host=".$servername.";dbname=".$db.";charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        $conn = null;
    }
}