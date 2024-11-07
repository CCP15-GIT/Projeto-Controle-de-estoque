<?php
$host = 'localhost'; // Geralmente 'localhost'
$dbname = 'informa5_projeto'; // Certifique-se de que este é o nome correto do banco de dados
$user = 'informa5_informa5'; // Nome de usuário do banco
$pass = 'Y-m-D%WC-lv,'; // Senha do banco

// Tenta se conectar ao banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
