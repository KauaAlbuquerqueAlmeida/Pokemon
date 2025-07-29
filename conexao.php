<?php
$host = 'localhost';
$dbname = 'pokemons'; // nome do banco
$user = 'root';       // usuário padrão do XAMPP
$pass = '';           // senha, normalmente vazia no XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Habilita erros como exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>

