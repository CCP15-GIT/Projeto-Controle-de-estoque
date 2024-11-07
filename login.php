<?php
session_start();
require 'banco.php'; // Conexão com o banco de dados

// Função para autenticar o usuário
function autenticarUsuario($pdo, $usuario, $senha) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->execute(['usuario' => $usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuario['usuario']; // Armazenar o nome do usuário na sessão
        header('Location: principal.html'); // Redirecionar para a tela principal
        exit;
    } else {
        return "Usuário ou senha incorretos.";
    }
}

// Processar formulário de login
if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $erro = autenticarUsuario($pdo, $usuario, $senha);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>

    <form method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" required>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit" name="login">Entrar</button>
    </form>

    <?php
    if (isset($erro)) {
        echo "<p>$erro</p>";
    }
    ?>
</body>
</html>
