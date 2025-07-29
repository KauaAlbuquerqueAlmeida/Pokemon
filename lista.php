<?php
$db = new PDO("sqlite:poke.db");
$search = $_GET['busca'] ?? '';
$stmt = $db->prepare("SELECT * FROM pokemons WHERE LOWER(nome) LIKE LOWER(?)");
$stmt->execute(["%$search%"]);
$pokemons = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Lista de Pokémons</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f8ff;
      padding: 20px;
    }
    .pokemon-list, .relatorio {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .pokemon-card {
      border-bottom: 1px solid #ccc;
      margin-bottom: 15px;
      padding-bottom: 10px;
    }
    img {
      max-width: 100px;
      margin-top: 10px;
    }
    a.button {
      display: inline-block;
      margin-right: 10px;
      padding: 5px 10px;
      background-color: #1976d2;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }
    a.button:hover {
      background-color: #1565c0;
    }
    .back-link {
      margin-bottom: 20px;
      display: inline-block;
    }
  </style>
</head>
<body>
  <h1>Pokémons Encontrados</h1>
  <a href="index.php" class="back-link">← Voltar para Cadastro</a>

  <form method="GET" action="lista.php">
    <input type="text" name="busca" placeholder="Buscar Pokémon por nome..." value="<?= htmlspecialchars($search) ?>" style="padding: 8px; width: 300px;">
    <button type="submit" style="padding: 8px;">Buscar</button>
  </form>

  <div class="pokemon-list">
    <?php if (count($pokemons) === 0): ?>
      <p>Nenhum Pokémon encontrado.</p>
    <?php else: ?>
      <?php foreach ($pokemons as $p): ?>
        <div class="pokemon-card">
          <h3><?= htmlspecialchars($p['nome']) ?> (<?= htmlspecialchars($p['tipo']) ?>)</h3>
          <p>Encontrado em <strong><?= htmlspecialchars($p['localizacao']) ?></strong> em <?= $p['data_registro'] ?></p>
          <?php if ($p['foto']): ?>
            <img src="fotos/<?= htmlspecialchars($p['foto']) ?>" alt="Foto de <?= htmlspecialchars($p['nome']) ?>">
          <?php endif; ?>
          <p>HP: <?= $p['hp'] ?> | Ataque: <?= $p['ataque'] ?> | Defesa: <?= $p['defesa'] ?></p>
          <p><em><?= nl2br(htmlspecialchars($p['observacoes'])) ?></em></p>
          <a class="button" href="editar.php?id=<?= $p['id'] ?>">Editar</a>
          <a class="button" href="excluir.php?id=<?= $p['id'] ?>" onclick="return confirm('Deseja excluir este Pokémon?')">Excluir</a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="relatorio">
    <h2>📊 Estatísticas por Tipo</h2>
    <?php
    $stats = $db->query("SELECT tipo, COUNT(*) as quantidade FROM pokemons GROUP BY tipo")->fetchAll();
    if (count($stats) === 0) {
      echo "<p>Nenhuma estatística para mostrar ainda.</p>";
    } else {
      foreach ($stats as $r) {
        echo "<p><strong>{$r['tipo']}:</strong> {$r['quantidade']} Pokémon(s)</p>";
      }
    }
    ?>
  </div>
</body>
</html>