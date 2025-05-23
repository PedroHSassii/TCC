<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_predio = $_POST['nome_predio'];
    $numero_sala = $_POST['numero_sala'];
    $andar = $_POST['andar'];
    $descricao = $_POST['descricao'];
    $usuario_id = $_SESSION['usuario_id'];

    // Prepara a consulta para inserir o novo ambiente
    $stmt = $conn->prepare("INSERT INTO ambientes (nome_predio, numero_sala, andar, descricao, usuario_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siisi", $nome_predio, $numero_sala, $andar, $descricao, $usuario_id);
    
    if ($stmt->execute()) {
        echo "Ambiente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar ambiente: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Ambiente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Cadastrar Novo Ambiente</h1>
        <form action="" method="POST" class="mt-4">
            <div class="form-group">
                <label for="nome_predio">Nome do Prédio:</label>
                <input type="text" name="nome_predio" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="numero_sala">Número da Sala:</label>
                <input type="number" name="numero_sala" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="andar">Andar:</label>
                <input type="number" name="andar" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
        </form>
        <div class="text-center mt-3">
            <a href="menu.php">Voltar para o menu</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
