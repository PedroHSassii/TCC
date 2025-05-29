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

// Inicializa a variável para a sala selecionada
$sala_selecionada = null;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala_id'])) {
    $sala_selecionada = $_POST['sala_id']; // Armazena a sala selecionada
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Controle de Ar-Condicionado</title>
    <style>
        body {
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
        }
        .btn-icon {
            width: 100px; /* Largura dos botões */
            height: 80px; /* Altura dos botões */
            font-size: 24px; /* Tamanho da fonte */
            margin: 10px; /* Margem entre os botões */
        }
        .icon-container {
            display: flex;
            justify-content: space-between; /* Espaça as colunas */
        }
        .column {
            display: flex;
            flex-direction: column; /* Coloca os botões em coluna */
            align-items: center; /* Centraliza os botões */
        }
    </style>
</head>
<body>
    <div class="container">
        
        <form method="POST" class="mt-4">
            <div class="form-group">
                <label for="sala_id">Selecione a Sala:</label>
                <select name="sala_id" id="sala_id" class="form-control" required>
                    <option value="">Selecione uma sala</option>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?php echo $sala['ambiente_id']; ?>" <?php echo ($sala['ambiente_id'] == $sala_selecionada) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sala['ambiente_nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Carregar Funções</button>
        </form>

        <?php if ($sala_selecionada): ?>
            <h2 class="text-center mt-4"><?php echo htmlspecialchars($salas[array_search($sala_selecionada, array_column($salas, 'ambiente_id'))]['ambiente_nome']); ?></h2>
            <div class="icon-container mt-4">
                <div class="column">
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Power')">Power</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Fan Speed')">Fan Speed</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Quiet')">Quiet</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Clean')">Clean</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Reset')">Reset</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Timer On')">Timer On</button>
                </div>

                <div class="column">
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Temp+')">Temp+</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Temp−')">Temp−</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Sleep')">Sleep</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Eco')">Eco</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('LED')">LED</button>
                </div>

                <div class="column">
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Mode')">Mode</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Swing')">Swing</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Turbo')">Turbo</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Anti-Fungus')">Anti-Fungus</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Clock')">Clock</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Timer Off')">Timer Off</button>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="logout.php" class="btn btn-warning">Logoff</a>
            <a href="menu.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function executarAcao(acao) {
            // Envia a ação para o servidor via AJAX
            $.ajax({
                url: 'acao.php',
                type: 'POST',
                data: {
                    acao: acao,
                    sala_id: <?php echo json_encode($sala_selecionada); ?> // Passa o ID da sala selecionada
                },
                success: function(response) {
                    alert("Ação executada: " + acao + "\n" + response);
                },
                error: function(xhr, status, error) {
                    alert("Erro ao executar a ação: " + error);
                }
            });
        }
    </script>
</body>
</html>
