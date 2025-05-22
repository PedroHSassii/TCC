<?php
session_start();
// Verificar se o usuário está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: controle.php"); // Redireciona para a página de controle se já estiver logado
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="autenticar.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
