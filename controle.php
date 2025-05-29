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
        $funcoes[$row['cod_tipofunc']] = $row['funcao']; // Mapeia a função
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
            justify-content: space-between; /* Espaça as colunas */
        }
        .column {
            display: flex;
            flex-direction: column; /* Coloca os botões em coluna */
            align-items: center; /* Centraliza os botões */
        }
        .disabled {
            background-color: #ccc; /* Cor de fundo para botões desativados */
            color: #666; /* Cor do texto para botões desativados */
            cursor: not-allowed; /* Cursor para indicar que o botão está desativado */
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
                <div class="column">
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Power">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['power']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['power']) ? 'disabled' : ''; ?>>Power</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Fan Speed">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['fan_speed']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['fan_speed']) ? 'disabled' : ''; ?>>Fan Speed</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Quiet">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['quiet']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['quiet']) ? 'disabled' : ''; ?>>Quiet</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Clean">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['clean']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['clean']) ? 'disabled' : ''; ?>>Clean</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Reset">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['reset']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['reset']) ? 'disabled' : ''; ?>>Reset</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Timer On">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['timer_on']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['timer_on']) ? 'disabled' : ''; ?>>Timer On</button>
                    </form>
                </div>

                <div class="column">
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Temp+">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['temp_plus']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['temp_plus']) ? 'disabled' : ''; ?>>Temp+</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Temp−">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['temp_minus']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['temp_minus']) ? 'disabled' : ''; ?>>Temp−</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Sleep">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['sleep']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['sleep']) ? 'disabled' : ''; ?>>Sleep</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Eco">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['eco']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['eco']) ? 'disabled' : ''; ?>>Eco</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="LED">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['led']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['led']) ? 'disabled' : ''; ?>>LED</button>
                    </form>
                </div>

                <div class="column">
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Mode">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['mode']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['mode']) ? 'disabled' : ''; ?>>Mode</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Swing">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['swing']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['swing']) ? 'disabled' : ''; ?>>Swing</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Turbo">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['turbo']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['turbo']) ? 'disabled' : ''; ?>>Turbo</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Anti-Fungus">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['anti_fungus']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['anti_fungus']) ? 'disabled' : ''; ?>>Anti-Fungus</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Clock">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['clock']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['clock']) ? 'disabled' : ''; ?>>Clock</button>
                    </form>
                    <form action="acao.php" method="POST">
                        <input type="hidden" name="acao" value="Timer Off">
                        <button type="submit" class="btn btn-primary btn-icon <?php echo !isset($funcoes['timer_off']) ? 'disabled' : ''; ?>" <?php echo !isset($funcoes['timer_off']) ? 'disabled' : ''; ?>>Timer Off</button>
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
