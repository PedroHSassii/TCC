<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0; // Verifica se o usuário é admin

    // Hash da senha
    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

    // Prepara a consulta para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO usuarios (email, senha, is_admin) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $email, $hashed_password, $is_admin);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Cadastrar Novo Usuário</h1>
        <form action="" method="POST" class="mt-4">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="is_admin" class="form-check-input" id="isAdmin" style="transform: scale(1.5);"> <!-- Aumenta o tamanho do checkbox -->
                <label class="form-check-label" for="isAdmin">Usuário Administrador</label>
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
