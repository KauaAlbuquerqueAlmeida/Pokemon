<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cadastro de Pokémons - Caçapava</title>
  <style>
    /* Reset básico */
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #b3e5fc, #81d4fa);
      margin: 0;
      padding: 0 10px 40px;
      color: #212121;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    header {
      margin-top: 20px;
      margin-bottom: 30px;
      text-align: center;
    }

    header img {
      max-width: 180px;
      height: auto;
      margin-bottom: 10px;
    }

    header h1 {
      font-weight: 700;
      font-size: 2rem;
      color: #01579b;
      text-shadow: 1px 1px 4px #0288d1;
    }

    main {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 40px;
      width: 100%;
      max-width: 1100px;
    }

    form {
      background: #ffffffcc;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      flex: 1 1 400px;
      max-width: 480px;
      transition: box-shadow 0.3s ease;
    }
    form:hover {
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    form h2 {
      margin-top: 0;
      margin-bottom: 15px;
      color: #0277bd;
      font-weight: 700;
      font-size: 1.6rem;
      text-align: center;
    }

    input[type="text"],
    input[type="date"],
    input[type="number"],
    input[type="file"],
    textarea {
      width: 100%;
      padding: 12px 14px;
      margin: 10px 0 18px;
      border-radius: 6px;
      border: 1.8px solid #90caf9;
      font-size: 1rem;
      font-weight: 500;
      color: #212121;
      transition: border-color 0.3s ease;
      resize: vertical;
      font-family: inherit;
    }
    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="number"]:focus,
    input[type="file"]:focus,
    textarea:focus {
      outline: none;
      border-color: #0288d1;
      box-shadow: 0 0 6px #0288d1aa;
    }

    textarea {
      min-height: 80px;
    }

    button {
      background: #0288d1;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 1.15rem;
      font-weight: 600;
      padding: 12px;
      cursor: pointer;
      width: 100%;
      transition: background 0.3s ease;
      box-shadow: 0 4px 12px rgba(2, 136, 209, 0.6);
    }
    button:hover {
      background: #0277bd;
      box-shadow: 0 6px 18px rgba(2, 119, 189, 0.8);
    }
    

    /* Placeholder mais suave */
    ::placeholder {
      color: #90a4ae;
      font-style: italic;
    }

    /* Estilos para formulário de busca */
    #busca-form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    #busca-form input[type="text"] {
      max-width: 380px;
      margin-bottom: 15px;
    }
    #busca-form button {
      max-width: 200px;
      padding: 10px;
    }

    /* Responsividade */
    @media (min-width: 700px) {
      #busca-form {
        flex-direction: row;
        gap: 12px;
      }
      #busca-form input[type="text"] {
        margin-bottom: 0;
        flex: 1;
      }
      #busca-form button {
        width: auto;
        max-width: none;
      }
    }
  </style>
</head>
<body>
  <header>
    <img src="./fotos/International_Pokémon_logo.svg.png" alt="Logo Pokémon Caçapava" />
    <h1>Cadastro de Pokémon Perdido</h1>
  </header>

  <main>
    <form action="cadastrar.php" method="POST" enctype="multipart/form-data" novalidate>
      <h2>Registrar Novo Pokémon</h2>
      <input type="text" name="nome" placeholder="Nome do Pokémon *" required />
      <input type="text" name="tipo" placeholder="Tipo (ex: Fogo, Água) *" required />
      <input type="text" name="localizacao" placeholder="Localização (bairro/rua) *" required />
      <input type="date" name="data_registro" required />
      <input type="number" name="hp" placeholder="HP" min="0" />
      <input type="number" name="ataque" placeholder="Ataque" min="0" />
      <input type="number" name="defesa" placeholder="Defesa" min="0" />
      <textarea name="observacoes" placeholder="Observações (comportamento, condição, etc.)"></textarea>
      <input type="file" name="foto" accept="image/*" />
      <button type="submit">Cadastrar Pokémon</button>
    </form>

    <form id="busca-form" method="GET" action="lista.php">
      <h2>Buscar Pokémons</h2>
      <input type="text" name="busca" placeholder="Buscar Pokémon por nome..." />
      <button type="submit">Buscar</button>
    </form>
  </main>
</body>
</html>
