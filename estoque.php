<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

require 'banco.php'; // Conexão com o banco de dados

$mensagem = "";

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $vencimento = $_POST['vencimento'];

    // Verifica se o produto já existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome = ?");
    $stmt->execute([$nome]);
    $produto = $stmt->fetch();

    if ($produto) {
        // Se o produto já existe, atualiza a quantidade
        $novaQuantidade = $produto['quantidade'] + $quantidade;
        $stmt = $pdo->prepare("UPDATE produtos SET quantidade = ?, vencimento = ? WHERE id = ?");
        $stmt->execute([$novaQuantidade, $vencimento, $produto['id']]);
        $mensagem = "Quantidade atualizada com sucesso!";
    } else {
        // Se o produto não existe, insere um novo
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, quantidade, vencimento) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $quantidade, $vencimento]);
        $mensagem = "Produto cadastrado com sucesso!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada de Produtos</title>
    <link rel="stylesheet" href="e.css">
</head>
<body>
    <header>
        <h1>Entrada de Produtos</h1>
    </header>

    <nav>
        <ul>
            <li><a href="principal.html">Voltar à Tela Principal</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <form action="estoque.php" method="post">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" required>

            <label for="vencimento">Data de Vencimento:</label>
            <input type="date" id="vencimento" name="vencimento" required>

            <button type="submit">Cadastrar Produto</button>
        </form>
        <?php if ($mensagem) { echo "<p class='message'>$mensagem</p>"; } ?>
    </div>
</body>
</html>
