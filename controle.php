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
            <div class="icon-container mt-4">
                <div class="column">
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Power">
                        <button type="submit" class="btn btn-primary btn-icon">Power</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Fan Speed">
                        <button type="submit" class="btn btn-primary btn-icon">Fan Speed</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Quiet">
                        <button type="submit" class="btn btn-primary btn-icon">Quiet</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Clean">
                        <button type="submit" class="btn btn-primary btn-icon">Clean</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Reset">
                        <button type="submit" class="btn btn-primary btn-icon">Reset</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Timer On">
                        <button type="submit" class="btn btn-primary btn-icon">Timer On</button>
                    </form>
                </div>

                <div class="column">
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Temp+">
                        <button type="submit" class="btn btn-primary btn-icon">Temp+</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Temp−">
                        <button type="submit" class="btn btn-primary btn-icon">Temp−</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Sleep">
                        <button type="submit" class="btn btn-primary btn-icon">Sleep</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Eco">
                        <button type="submit" class="btn btn-primary btn-icon">Eco</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="LED">
                        <button type="submit" class="btn btn-primary btn-icon">LED</button>
                    </form>
                </div>

                <div class="column">
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Mode">
                        <button type="submit" class="btn btn-primary btn-icon">Mode</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Swing">
                        <button type="submit" class="btn btn-primary btn-icon">Swing</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Turbo">
                        <button type="submit" class="btn btn-primary btn-icon">Turbo</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Anti-Fungus">
                        <button type="submit" class="btn btn-primary btn-icon">Anti-Fungus</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Clock">
                        <button type="submit" class="btn btn-primary btn-icon">Clock</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Timer Off">
                        <button type="submit" class="btn btn-primary btn-icon">Timer Off</button>
                    </form>
                </div>
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
