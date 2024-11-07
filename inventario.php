<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

require 'banco.php'; // Conexão com o banco de dados

// Funções para gerenciar produtos
function listarProdutos($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM produtos ORDER BY nome ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function editarProduto($pdo, $id, $nome, $quantidade, $vencimento) {
    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, quantidade = ?, vencimento = ? WHERE id = ?");
    $stmt->execute([$nome, $quantidade, $vencimento, $id]);
}

function removerProduto($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['editar'])) {
        editarProduto($pdo, $_POST['id'], $_POST['nome'], $_POST['quantidade'], $_POST['vencimento']);
    } elseif (isset($_POST['remover'])) {
        removerProduto($pdo, $_POST['id']);
    }
}

$produtos = listarProdutos($pdo);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventário</title>
    <link rel="stylesheet" href="in.css">
</head>
<body>
    <header>
        <h1>Inventário de Produtos</h1>
    </header>

    <nav>
        <ul>
            <li><a href="principal.html">Voltar à Tela Principal</a></li>
        </ul>
    </nav>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Produto</th>
                    <th>Quantidade</th>
                    <th>Data de Vencimento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td><?= $produto['quantidade'] ?></td>
                        <td><?= date('d/m/Y', strtotime($produto['vencimento'])) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                <input type="text" name="nome" placeholder="Novo nome" required>
                                <input type="number" name="quantidade" placeholder="Nova quantidade" required>
                                <input type="date" name="vencimento" required>
                                <button type="submit" name="editar">Editar</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                <button type="submit" name="remover" onclick="return confirm('Tem certeza que deseja remover este produto?');">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
