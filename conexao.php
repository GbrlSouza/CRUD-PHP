<?php
$db   = 'cadastro_db';
$host = 'localhost';
$user = 'root';
$pass = ''; 
$port = 3306;
$charset = 'utf8mb4'; 

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try { $pdo = new PDO($dsn, $user, $pass, $options); }
catch (\PDOException $e) { die("Falha na conexão com o banco de dados: " . $e -> getMessage()); }