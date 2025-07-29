<?php
try {
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

    // Verifica se foi enviado o formulário corretamente
    if (!isset($_POST['nome']) || empty($_POST['nome'])) {
        die("Erro: Nome do Pokémon é obrigatório.");
    }

    // Pega os dados
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $localizacao = $_POST['localizacao'];
    $data = $_POST['data_registro'];
    $hp = $_POST['hp'] ?? null;
    $ataque = $_POST['ataque'] ?? null;
    $defesa = $_POST['defesa'] ?? null;
    $obs = $_POST['observacoes'] ?? null;

    // Foto
    $fotoNome = $_FILES['foto']['name'] ?? null;
    if ($_FILES['foto']['tmp_name']) {
        if (!is_dir("fotos")) {
            mkdir("fotos", 0777, true);
        }
        move_uploaded_file($_FILES['foto']['tmp_name'], "fotos/" . $fotoNome);
    }

    // Inserção no banco
    $stmt = $db->prepare("INSERT INTO pokemons 
        (nome, tipo, localizacao, data_registro, hp, ataque, defesa, observacoes, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $ok = $stmt->execute([
        $nome, $tipo, $localizacao, $data, $hp, $ataque, $defesa, $obs, $fotoNome
    ]);

    if ($ok) {
        // Redireciona para o index
        header("Location: index.html");
        exit;
    } else {
        echo "Erro ao cadastrar Pokémon.";
    }
} catch (Exception $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
}
?>
