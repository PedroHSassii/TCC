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

// Armazena as salas em um array
while ($row = $result->fetch_assoc()) {
    $salas[] = $row;
}

// Inicializa a variável para a sala selecionada
$sala_selecionada = null;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala_id'])) {
    $sala_selecionada = $_POST['sala_id']; // Armazena a sala selecionada
}

// Obter dados do ambiente selecionado
if ($sala_selecionada) {
    $stmt = $conn->prepare("SELECT modo, temperatura, velocidade, timer, status FROM ambientes WHERE id = ?");
    $stmt->bind_param("i", $sala_selecionada);
    $stmt->execute();
    $stmt->bind_result($modo, $temperatura, $velocidade, $timer, $status);
    $stmt->fetch();
    $stmt->close();
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
        .monitor {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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
    <script>
        function executarAcao(acao) {
            const data = {
                acao: acao,
                modo: document.getElementById('modo').value,
                temperatura: document.getElementById('temperatura').value,
                velocidade: document.getElementById('velocidade').value,
                timer: document.getElementById('timer').checked ? 1 : 0,
                status: document.getElementById('status').checked ? 1 : 0,
                sala_id: <?php echo json_encode($sala_selecionada); ?>
            };

            fetch('acao.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Para depuração
                alert(data); // Exibe a resposta do servidor
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <?php if (!$sala_selecionada): ?>
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
        <?php else: ?>
            <div class="monitor">
                <h4>Monitor de Controle</h4>
                <p><strong>Modo de Operação:</strong> <?php echo isset($modo) ? htmlspecialchars($modo) : 'N/A'; ?></p>
                <p><strong>Temperatura:</strong> <?php echo isset($temperatura) ? htmlspecialchars($temperatura) . ' °C' : 'N/A'; ?></p>
                <p><strong>Velocidade do Ventilador:</strong> <?php echo isset($velocidade) ? htmlspecialchars($velocidade) : 'N/A'; ?></p>
                <p><strong>Timer:</strong> <?php echo isset($timer) ? ($timer ? 'Ativado' : 'Desativado') : 'N/A'; ?></p>
                <p><strong>Status de Energia:</strong> <?php echo isset($status) ? ($status ? 'Ligado' : 'Desligado') : 'N/A'; ?></p>
            </div>

            <h2 class="text-center mt-4"><?php echo htmlspecialchars($salas[array_search($sala_selecionada, array_column($salas, 'ambiente_id'))]['ambiente_nome']); ?></h2>
 <div class="icon-container mt-4">
                <div class="column">
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Power')" <?php echo in_array(1, $funcoes_mapeadas) ? '' : 'disabled'; ?>>Power</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Fan Speed')" <?php echo in_array(2, $funcoes_mapeadas) ? '' : 'disabled'; ?>>Fan Speed</button>
                </div>

                <div class="column">
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Temp+')" <?php echo in_array(7, $funcoes_mapeadas) ? '' : 'disabled'; ?>>Temp+</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Temp−')" <?php echo in_array(8, $funcoes_mapeadas) ? '' : 'disabled'; ?>>Temp−</button>
                </div>

                <div class="column">
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Mode')" <?php echo in_array(12, $funcoes_mapeadas) ? '' : 'disabled'; ?>>Mode</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Swing')" <?php echo in_array(13, $funcoes_mapeadas) ? '' : 'disabled'; ?>>Swing</button>
                    <button class="btn btn-primary btn-icon" onclick="executarAcao('Turbo')" <?php echo in_array(14, $funcoes_mapeadas) ? '' : 'style="display:none;"'; ?>>Turbo</button>
                </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
