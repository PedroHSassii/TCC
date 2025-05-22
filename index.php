<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sistema de Automação e Controle de Temperatura</title>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo ao Sistema de Automação e Controle de Temperatura!</h1>
        <p>Controle seu ar-condicionado de forma fácil e eficiente.</p>
        
        <div class="button-group">
            <a href="login.php" class="btn btn-login">Login</a>
            <a href="cadastrar_ambiente.php" class="btn btn-register">Cadastrar Ambiente</a>
        </div>
    </div>
</body>
</html>
