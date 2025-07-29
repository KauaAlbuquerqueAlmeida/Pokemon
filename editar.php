<?php
$db = new PDO("sqlite:poke.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Pega o ID via GET ou POST
$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    die("ID do Pokémon não fornecido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza os dados
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $localizacao = $_POST['localizacao'];
    $data = $_POST['data_registro'];
    $hp = $_POST['hp'] ?? null;
    $ataque = $_POST['ataque'] ?? null;
    $defesa = $_POST['defesa'] ?? null;
    $obs = $_POST['observacoes'] ?? null;

    // Foto: se enviou nova foto, substituir
    $fotoNome = $_POST['foto_atual'] ?? null;
    if (!empty($_FILES['foto']['tmp_name'])) {
        if (!is_dir("fotos")) {
            mkdir("fotos", 0777, true);
        }
        $fotoNome = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "fotos/" . $fotoNome);
    }

    $stmt = $db->prepare("UPDATE pokemons SET
        nome = ?, tipo = ?, localizacao = ?, data_registro = ?, hp = ?, ataque = ?, defesa = ?, observacoes = ?, foto = ?
        WHERE id = ?");
    $ok = $stmt->execute([
        $nome, $tipo, $localizacao, $data, $hp, $ataque, $defesa, $obs, $fotoNome, $id
    ]);

    if ($ok) {
        header("Location: lista.php");
        exit;
    } else {
        echo "Erro ao atualizar Pokémon.";
    }
} else {
    // Carrega dados atuais para o formulário
    $stmt = $db->prepare("SELECT * FROM pokemons WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();

    if (!$p) {
        die("Pokémon não encontrado.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Pokémon - <?= htmlspecialchars($p['nome']) ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f8ff;
      padding: 20px;
    }
    form {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      max-width: 500px;
    }
    input, textarea, button {
      display: block;
      margin: 10px 0;
      padding: 8px;
      width: 100%;
    }
    img {
      max-width: 150px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <h1>Editar Pokémon - <?= htmlspecialchars($p['nome']) ?></h1>
  <form action="editar.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $p['id'] ?>">
    <input type="hidden" name="foto_atual" value="<?= htmlspecialchars($p['foto']) ?>">

    <input type="text" name="nome" placeholder="Nome do Pokémon" required value="<?= htmlspecialchars($p['nome']) ?>">
    <input type="text" name="tipo" placeholder="Tipo (ex: Fogo, Água)" required value="<?= htmlspecialchars($p['tipo']) ?>">
    <input type="text" name="localizacao" placeholder="Localização" required value="<?= htmlspecialchars($p['localizacao']) ?>">
    <input type="date" name="data_registro" required value="<?= $p['data_registro'] ?>">
    <input type="number" name="hp" placeholder="HP" value="<?= $p['hp'] ?>">
    <input type="number" name="ataque" placeholder="Ataque" value="<?= $p['ataque'] ?>">
    <input type="number" name="defesa" placeholder="Defesa" value="<?= $p['defesa'] ?>">
    <textarea name="observacoes" placeholder="Observações"><?= htmlspecialchars($p['observacoes']) ?></textarea>

    <?php if ($p['foto']): ?>
      <p>Foto atual:</p>
      <img src="fotos/<?= htmlspecialchars($p['foto']) ?>" alt="Foto do Pokémon">
    <?php endif; ?>

    <label>Alterar foto (opcional):</label>
    <input type="file" name="foto">

    <button type="submit">Salvar Alterações</button>
  </form>

  <p><a href="lista.php">Voltar para lista</a></p>
</body>
</html>