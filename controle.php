<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Controle de Ar-Condicionado</title>
</head>
<body>
    <h1>Controle de Ar-Condicionado</h1>
    <form action="acao.php" method="POST">
        <label for="temperatura">Temperatura:</label>
        <input type="number" name="temperatura" required>
        <button type="submit" name="acao" value="ligar">Ligar</button>
        <button type="submit" name="acao" value="desligar">Desligar</button>
    </form>
</body>
</html>
