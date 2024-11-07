<?php
session_start();

// Verifica se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Usuário e senha padrão
    if ($usuario == 'admin' && $senha == 'admin') {
        $_SESSION['logado'] = true;
        header('Location: principal.html');
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
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
    <div class="login-container">
	  <center><img src="img\logo.jpeg" width="200"></center>
	
        <h1>Login</h1>
        <form action="index.php" method="post">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit">Entrar</button>
        </form>
        <?php if (isset($erro)) { echo "<p style='color:red;'>$erro</p>"; } ?>
    </div>
</body>
</html>
