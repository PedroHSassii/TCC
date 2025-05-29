<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obter as salas disponíveis
$stmt = $conn->prepare("SELECT a.id AS ambiente_id, CONCAT(p.nome, ' - Sala ', a.numero_sala) AS ambiente_nome 
                         FROM ambientes a 
                         JOIN predios p ON a.predio_id = p.id");
$stmt->execute();
$result = $stmt->get_result();
$salas = [];

while ($row = $result->fetch_assoc()) {
    $salas[] = $row;
}

// Obter funções mapeadas para a sala selecionada
$funcoes = [];
if (isset($_POST['sala_id'])) {
    $sala_id = $_POST['sala_id'];
    $stmt = $conn->prepare("SELECT f.cod_tipofunc, f.funcao FROM funcoes f 
                             JOIN ir_codes ic ON f.cod_tipofunc = ic.cod_tipofunc 
                             WHERE ic.cod_ambiente = ?");
    $stmt->bind_param("i", $sala_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $funcoes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Controle de Ar-Condicionado</title>
    <style>
        .btn-icon {
            width: 100px; /* Largura dos botões */
            height: 100px; /* Altura dos botões */
            font-size: 24px; /* Tamanho da fonte */
            margin: 10px; /* Margem entre os botões */
        }
        .icon-container {
            display: flex;
            flex-wrap: wrap; /* Permite que os botões se ajustem em várias linhas */
            justify-content: center; /* Centraliza os botões */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Controle de Ar-Condicionado</h1>
        
        <form method="POST" class="mt-4">
            <div class="form-group">
                <label for="sala_id">Selecione a Sala:</label>
                <select name="sala_id" id="sala_id" class="form-control" required>
                    <option value="">Selecione uma sala</option>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?php echo $sala['ambiente_id']; ?>"><?php echo htmlspecialchars($sala['ambiente_nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Carregar Funções</button>
        </form>

        <?php if (!empty($funcoes)): ?>
            <div class="icon-container mt-4">
                <?php foreach ($funcoes as $funcao): ?>
                    <form action="acao.php" method="POST" class="text-center">
                        <input type="hidden" name="acao" value="<?php echo htmlspecialchars($funcao['funcao']); ?>">
                        <button type="submit" class="btn btn-primary btn-icon">
                            <i class="fas fa-thermometer-half"></i> <!-- Ícone representativo -->
                            <br><?php echo htmlspecialchars($funcao['funcao']); ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="logout.php" class="btn btn-warning">Logoff</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
