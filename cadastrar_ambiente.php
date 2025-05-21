<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("INSERT INTO ambientes (nome, usuario_id) VALUES (?, ?)");
    $stmt->bind_param("si", $nome, $usuario_id);
    $stmt->execute();
    $stmt->close();
    echo "Ambiente cadastrado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Ambiente</title>
</head>
<body>
    <h1>Cadastrar Novo Ambiente</h1>
    <form action="" method="POST">
        <label for="nome">Nome do Ambiente:</label>
        <input type="text" name="nome" required>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
