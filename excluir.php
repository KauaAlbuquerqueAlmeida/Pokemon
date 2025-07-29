<?php
if (!isset($_GET['id'])) {
    die("ID do Pokémon não fornecido.");
}

$id = $_GET['id'];

$db = new PDO("sqlite:poke.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Opcional: buscar foto para apagar o arquivo (se quiser)
$stmt = $db->prepare("SELECT foto FROM pokemons WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if ($p && $p['foto']) {
    $caminhoFoto = "fotos/" . $p['foto'];
    if (file_exists($caminhoFoto)) {
        unlink($caminhoFoto); // Apaga o arquivo da foto
    }
}

// Excluir registro
$stmt = $db->prepare("DELETE FROM pokemons WHERE id = ?");
$stmt->execute([$id]);

header("Location: lista.php");
exit;
?>