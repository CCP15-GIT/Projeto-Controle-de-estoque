<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

require 'banco.php'; // Conexão com o banco de dados

// Função para listar movimentações de produtos
function listarMovimentacoes($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM movimentacoes ORDER BY data DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para contar o nível de produtos
function contarProdutos($pdo) {
    $stmt = $pdo->prepare("SELECT nome, SUM(quantidade) as total FROM produtos GROUP BY nome");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$movimentacoes = listarMovimentacoes($pdo);
$produtos = contarProdutos($pdo);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link rel="stylesheet" href="r.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Relatórios de Estoque</h1>
    </header>

    <nav>
        <ul>
            <li><a href="principal.html">Voltar à Tela Principal</a></li>
        </ul>
    </nav>

    <section class="movimentacoes">
        <h2>Movimentações de Produtos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimentacoes as $movimentacao): ?>
                    <tr>
                        <td><?= $movimentacao['id'] ?></td>
                        <td><?= htmlspecialchars($movimentacao['produto']) ?></td>
                        <td><?= $movimentacao['quantidade'] ?></td>
                        <td><?= date('d/m/Y', strtotime($movimentacao['data'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="nivel-produtos">
        <h2>Nível de Produtos</h2>
        <canvas id="nivelProdutosChart"></canvas>
        <script>
            const ctx = document.getElementById('nivelProdutosChart').getContext('2d');
            const produtos = <?= json_encode($produtos) ?>;

            const labels = produtos.map(produto => produto.nome);
            const data = produtos.map(produto => produto.total);

            const nivelProdutosChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total em Estoque',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </section>
</body>
</html>
