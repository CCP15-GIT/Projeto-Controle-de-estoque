<?php
session_start();
require 'banco.php'; // Conexão com o banco de dados

// Função para cadastrar um novo usuário
function cadastrarUsuario($pdo, $usuario, $senha) {
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)");
    $stmt->execute(['usuario' => $usuario, 'senha' => password_hash($senha, PASSWORD_DEFAULT)]);
}

// Processar formulário de cadastro de usuário
if (isset($_POST['cadastrar_usuario'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verificar se o usuário já existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->execute(['usuario' => $usuario]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['mensagem'] = "Usuário já existe!";
    } else {
        cadastrarUsuario($pdo, $usuario, $senha);
        $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
        header('Location: login.php'); // Redirecionar para a página de login
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastrar Usuário</h1>
    </header>

    <form method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit" name="cadastrar_usuario">Cadastrar</button>
    </form>

    <?php
    if (isset($_SESSION['mensagem'])) {
        echo "<p>{$_SESSION['mensagem']}</p>";
        unset($_SESSION['mensagem']);
    }
    ?>
</body>
</html>
