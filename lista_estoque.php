<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

require 'banco.php'; // Conexão com o banco de dados

// Seleciona todos os produtos do banco de dados
$stmt = $pdo->prepare("SELECT * FROM produtos ORDER BY vencimento ASC");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dataAtual = date('Y-m-d'); // Data atual para verificar vencimento
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estoque</title>
    <link rel="stylesheet" href="l.css">
    <style>
        .vencido {
            color: red;
        }

        /* Estilização básica para responsividade */
        body {
            background-color: #ffffff; /* Fundo branco */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center; /* Centraliza o cabeçalho */
            padding: 20px;
        }

        nav {
            text-align: center; /* Centraliza o menu de navegação */
            margin-bottom: 20px; /* Espaçamento embaixo do menu */
        }

        nav ul {
            list-style: none; /* Remove marcadores da lista */
            padding: 0; /* Remove espaçamento padrão */
        }

        nav li {
            display: inline; /* Exibe os itens do menu em linha */
            margin: 0 15px; /* Espaçamento entre os itens do menu */
        }

        table {
            width: 100%; /* Largura total da tabela */
            border-collapse: collapse; /* Remove espaçamento entre as bordas das células */
            margin: 20px 0; /* Margem em cima e embaixo da tabela */
        }

        table th, table td {
            padding: 10px; /* Espaçamento interno das células */
            border: 1px solid #ccc; /* Borda das células */
            text-align: left; /* Alinhamento à esquerda do texto */
        }

        table th {
            background-color: #f2f2f2; /* Cor de fundo dos cabeçalhos */
        }

        /* Responsividade */
        @media (max-width: 768px) {
            nav li {
                display: block; /* Coloca os itens do menu em coluna em telas menores */
                margin: 5px 0; /* Margem em cima e embaixo dos itens do menu */
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Lista de Estoque</h1>
    </header>

    <nav>
        <ul>
            <li><a href="principal.html">Voltar à Tela Principal</a></li>
        </ul>
    </nav>

    <table>
        <tr>
            <th>Nome do Produto</th>
            <th>Quantidade</th>
            <th>Data de Vencimento</th>
        </tr>
        <?php foreach ($produtos as $produto): ?>
            <tr class="<?= ($produto['vencimento'] < $dataAtual) ? 'vencido' : '' ?>">
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td><?= $produto['quantidade'] ?></td>
                <td><?= date('d/m/Y', strtotime($produto['vencimento'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
