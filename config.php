<?php

$host = 'localhost';
$dbname = 'esas';
$username = 'root';
$password =''; 
//u593341949.dev_esas
//NBSCRomans828

// $port =3307;
// $password ='root'; 
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'esas');

try {
    // $pdo = new PDO("mysql:host=$host; port=$port; dbname=$dbname", $username, $password);
    $pdo = new PDO("mysql:host=$host;  dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>