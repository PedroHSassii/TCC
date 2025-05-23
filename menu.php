<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o usuário é admin
$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT is_admin FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();
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
        <h1 class="text-center">Controle de Ar-Condicionado</h1>

        <!-- Menu de navegação -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Menu</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="menu.php">MENU</a>
                    </li>
                    <?php if ($is_admin): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="controle.php">Controlar Ambiente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cadastrar_usuario.php">Cadastrar Usuário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cadastrar_ambiente.php">Cadastrar Ambiente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="historico.php">Histórico</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logoff</a>
                    </li>
                </ul>
            </div>
        </nav>

</body>
</html>
