<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Pokémons</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f8ff;
      padding: 20px;
    }
    form, .pokemon-list, .relatorio {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    input, textarea, button {
      display: block;
      margin: 10px 0;
      padding: 8px;
      width: 100%;
      max-width: 500px;
    }
    img { max-width: 100px; }
  </style>
</head>
<body>
  <h1>Cadastro de Pokémon Perdido</h1>
  <form action="cadastrar.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nome" placeholder="Nome do Pokémon" required>
    <input type="text" name="tipo" placeholder="Tipo (ex: Fogo, Água)" required>
    <input type="text" name="localizacao" placeholder="Localização" required>
    <input type="date" name="data_registro" required>
    <input type="number" name="hp" placeholder="HP">
    <input type="number" name="ataque" placeholder="Ataque">
    <input type="number" name="defesa" placeholder="Defesa">
    <textarea name="observacoes" placeholder="Observações"></textarea>
    <input type="file" name="foto">
    <button type="submit">Cadastrar Pokémon</button>
  </form>

  <form method="GET" action="lista.php">
    <input type="text" name="busca" placeholder="Buscar Pokémon por nome...">
    <button type="submit">Buscar</button>
  </form>
</body>
</html>

<!-- cadastrar.php -->
<?php
$db = new PDO("sqlite:poke.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS pokemons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    tipo TEXT NOT NULL,
    localizacao TEXT NOT NULL,
    data_registro DATE NOT NULL,
    hp INTEGER,
    ataque INTEGER,
    defesa INTEGER,
    observacoes TEXT,
    foto TEXT
);");

if (!isset($_POST['nome']) || empty($_POST['nome'])) {
    die("Nome obrigatório!");
}

$fotoNome = $_FILES['foto']['name'] ?? null;

if ($_FILES['foto']['tmp_name']) {
    move_uploaded_file($_FILES['foto']['tmp_name'], "fotos/" . $fotoNome);
}

$stmt = $db->prepare("INSERT INTO pokemons (nome, tipo, localizacao, data_registro, hp, ataque, defesa, observacoes, foto)
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
    $fotoNome
]);

header("Location: lista.php");
?>

<!-- lista.php -->
<?php
$db = new PDO("sqlite:poke.db");
$search = $_GET['busca'] ?? '';
$stmt = $db->prepare("SELECT * FROM pokemons WHERE nome LIKE ?");
$stmt->execute(["%$search%"]);
$pokemons = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Lista de Pokémons</title>
</head>
<body>
  <h1>Pokémons Encontrados</h1>
  <a href="index.html">Voltar</a>
  <div class="pokemon-list">
    <?php foreach ($pokemons as $p): ?>
      <div style="border-bottom:1px solid #ccc; margin-bottom:10px;">
        <h3><?= htmlspecialchars($p['nome']) ?> (<?= htmlspecialchars($p['tipo']) ?>)</h3>
        <p>Encontrado em <?= htmlspecialchars($p['localizacao']) ?>, <?= $p['data_registro'] ?></p>
        <?php if ($p['foto']): ?>
          <img src="fotos/<?= htmlspecialchars($p['foto']) ?>">
        <?php endif; ?>
        <p>HP: <?= $p['hp'] ?> | Ataque: <?= $p['ataque'] ?> | Defesa: <?= $p['defesa'] ?></p>
        <p><em><?= $p['observacoes'] ?></em></p>
        <a href="editar.php?id=<?= $p['id'] ?>">Editar</a> | 
        <a href="excluir.php?id=<?= $p['id'] ?>" onclick="return confirm('Deseja excluir?')">Excluir</a>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="relatorio">
    <h2>Estatísticas</h2>
    <?php
    $stats = $db->query("SELECT tipo, COUNT(*) as quantidade FROM pokemons GROUP BY tipo")->fetchAll();
    foreach ($stats as $r) {
      echo "<p>{$r['tipo']}: {$r['quantidade']} Pokémon(s)</p>";
    }
    ?>
  </div>
</body>
</html>