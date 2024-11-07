<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

require 'banco.php'; // Conexão com o banco de dados

// Função para cadastrar um novo usuário
function cadastrarUsuario($pdo, $usuario, $senha) {
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)");
    $stmt->execute(['usuario' => $usuario, 'senha' => password_hash($senha, PASSWORD_DEFAULT)]);
}

// Função para alterar a senha do usuário
function alterarSenha($pdo, $usuario, $novaSenha) {
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE usuario = :usuario");
    $stmt->execute(['senha' => password_hash($novaSenha, PASSWORD_DEFAULT), 'usuario' => $usuario]);
}

// Função para gerar backup em Excel
function gerarBackup($pdo) {
    $stmt = $pdo->query("SELECT * FROM produtos");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Cabeçalhos do arquivo Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="backup_produtos.xls"');

    // Criar a estrutura do Excel
    echo "ID\tNome\tQuantidade\tVencimento\n"; // Cabeçalhos das colunas
    foreach ($produtos as $produto) {
        echo "{$produto['id']}\t{$produto['nome']}\t{$produto['quantidade']}\t{$produto['vencimento']}\n";
    }
    exit;
}

// Processar formulário de cadastro de usuário
if (isset($_POST['cadastrar_usuario'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    cadastrarUsuario($pdo, $usuario, $senha);
}

// Processar formulário de alteração de senha
if (isset($_POST['alterar_senha'])) {
    $usuario = $_SESSION['usuario']; // Assume que o nome de usuário está na sessão
    $novaSenha = $_POST['nova_senha'];
    alterarSenha($pdo, $usuario, $novaSenha);
}

// Processar requisição de backup
if (isset($_POST['backup'])) {
    gerarBackup($pdo);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Configurações</title>
    <link rel="stylesheet" href="conf.css">
</head>
<body>
    <header>
        <h1>Tela de Backup</h1>
    </header>

    <nav>
        <ul>
            <li><a href="principal.html">Voltar à Tela Principal</a></li>
        </ul>
    </nav>
<!-- inibido
    <h2>Cadastrar Usuário</h2>
    <form method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit" name="cadastrar_usuario">Cadastrar</button>
    </form>

    <h2>Alterar Senha</h2>
    <form method="post">
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" required>
        <button type="submit" name="alterar_senha">Alterar Senha</button>
    </form> -->
    <center>
    <h2>Backup</h2>
    <form method="post">
        <button type="submit" name="backup">Gerar Backup em Excel</button>
    </form> </center>

</body>
</html>
