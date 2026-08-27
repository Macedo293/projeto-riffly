<?php
// config/conexao.php
$host = "localhost";
$usuario = "root";
$senha = ""; // XAMPP usa senha vazia por padrão
$banco = "riffly_db";

try {
    $conn = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>