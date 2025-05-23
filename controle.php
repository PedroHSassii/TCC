<?php
session_start();
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Controle de Ar-Condicionado</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1>Controle de Ar-Condicionado</h1>
        <form action="acao.php" method="POST" class="mt-4">
            <div class="form-group">
                <label for="temperatura">Temperatura:</label>
                <input type="number" name="temperatura" class="form-control" required>
            </div>
            <button type="submit" name="acao" value="ligar" class="btn btn-success">Ligar</button>
            <button type="submit" name="acao" value="desligar" class="btn btn-danger">Desligar</button>
        </form>
        <div id="temperatura-atual" class="mt-4">
            <h2>Temperatura Atual: <span id="temp"></span> °C</h2>
        </div>
        <div class="text-center mt-4">
            <a href="logout.php" class="btn btn-warning">Logoff</a>
        </div>
    </div>

    <script>
        setInterval(() => {
            fetch('get_temperatura.php') // Crie este arquivo para retornar a temperatura atual
                .then(response => response.json())
                .then(data => {
                    document.getElementById('temp').innerText = data.temperatura;
                });
        }, 5000); // Atualiza a cada 5 segundos
    </script>
</body>
</html>
