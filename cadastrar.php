<?php
// Conexão com o banco de dados MySQL (phpMyAdmin)
$host = 'localhost';
$db   = 'pokemons'; // Substitua pelo nome real do seu banco
$user = 'root';
$pass = ''; // Altere se sua senha for diferente
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Erro na conexão: ' . $e->getMessage();
    exit;
}

// Criar a tabela se não existir
$pdo->exec("CREATE TABLE IF NOT EXISTS pokemons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    data_registro DATE NOT NULL,
    hp INT,
    ataque INT,
    defesa INT,
    observacoes TEXT,
    foto TEXT
)");

// Cadastro de Pokémon
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $stmt = $pdo->prepare("INSERT INTO pokemons (nome, tipo, localizacao, data_registro, hp, ataque, defesa, observacoes, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nome'],
        $_POST['tipo'],
        $_POST['localizacao'],
        $_POST['data_registro'],
        $_POST['hp'],
        $_POST['ataque'],
        $_POST['defesa'],
        $_POST['observacoes'],
        $_POST['foto']
    ]);
    header('Location: index.php?cadastro=ok');
    exit;
}

// Listagem com busca
$pokemons = [];
$busca = $_GET['busca'] ?? '';
if ($busca !== '') {
    $stmt = $pdo->prepare("SELECT * FROM pokemons WHERE nome LIKE ?");
    $stmt->execute(["%$busca%"]);
    $pokemons = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT * FROM pokemons");
    $pokemons = $stmt->fetchAll();
}

// Exclusão
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM pokemons WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: lista.php');
    exit;
}

// Edição (formulário + update)
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM pokemons WHERE id = ?");
    $stmt->execute([$id]);
    $pokemon = $stmt->fetch();
    include 'editar.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
    $stmt = $pdo->prepare("UPDATE pokemons SET nome=?, tipo=?, localizacao=?, data_registro=?, hp=?, ataque=?, defesa=?, observacoes=?, foto=? WHERE id = ?");
    $stmt->execute([
        $_POST['nome'], $_POST['tipo'], $_POST['localizacao'], $_POST['data_registro'],
        $_POST['hp'], $_POST['ataque'], $_POST['defesa'], $_POST['observacoes'], $_POST['foto'], $_POST['id']
    ]);
    header('Location: lista.php');
    exit;
}

?>
