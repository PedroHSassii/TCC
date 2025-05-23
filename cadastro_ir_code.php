<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verifica se o usuário está logado e é admin
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

if (!$is_admin) {
    echo "Acesso negado. Apenas administradores podem cadastrar códigos IR.";
    exit();
}

// Obter ambientes com o nome do prédio e número da sala concatenados
$ambientes = [];
$stmt = $conn->prepare("
    SELECT a.id, CONCAT(p.nome, ' - Sala ', a.numero_sala) AS ambiente_nome 
    FROM ambientes a 
    JOIN predios p ON a.predio_id = p.id
");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $ambientes[] = $row;
}

// Obter funções
$funcoes = [];
$result = $conn->query("SELECT cod_tipofunc, funcao FROM funcoes");
while ($row = $result->fetch_assoc()) {
    $funcoes[] = $row;
}

// Lógica para ler o código IR (simulação)
$codigo_ir = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ler'])) {
        // Aqui você deve implementar a lógica para ler o código IR do dispositivo
        // Para fins de exemplo, vamos simular um código IR
        $codigo_ir = '0x20DF10EF'; // Simulação de código IR lido
    } elseif (isset($_POST['salvar'])) {
        $cod_tipofunc = $_POST['funcao'];
        $cod_ambiente = $_POST['ambiente'];
        $codigo_ir = $_POST['codigo_ir'];

        // Salvar no banco de dados
        $stmt = $conn->prepare("INSERT INTO ir_codes (cod_tipofunc, cod_ambiente, codigo_ir) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $cod_tipofunc, $cod_ambiente, $codigo_ir);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Código IR salvo com sucesso!');</script>";
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
    <title>Cadastro de Código IR</title>
    <style>
        /* Estilos adicionais para centralizar e aumentar os botões */
        button {
            padding: 15px; /* Aumenta o padding para botões */
            font-size: 16px; /* Aumenta o tamanho da fonte */
            width: 100%; /* Faz com que os botões ocupem toda a largura do contêiner */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Cadastro de Código IR</h1>
        <form method="POST">
            <div class="form-group">
                <label for="ambiente">Ambiente</label>
                <select class="form-control" id="ambiente" name="ambiente" required>
                    <option value="">Selecione um ambiente</option>
                    <?php foreach ($ambientes as $ambiente): ?>
                        <option value="<?php echo $ambiente['id']; ?>"><?php echo htmlspecialchars($ambiente['ambiente_nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="funcao">Função</label>
                <select class="form-control" id="funcao" name="funcao" required>
                    <option value="">Selecione uma função</option>
                    <?php foreach ($funcoes as $funcao): ?>
                        <option value="<?php echo $funcao['cod_tipofunc']; ?>"><?php echo $funcao['funcao']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="codigo_ir">Código IR</label>
                <input type="text" class="form-control" id="codigo_ir" name="codigo_ir" value="<?php echo htmlspecialchars($codigo_ir); ?>" readonly>
            </div>

            <div class="text-center" style="margin-bottom: 15px;"> <!-- Centraliza os botões -->
                <button type="submit" name="ler" class="btn btn-primary">Ler</button>
            </div>

            <div class="text-center"> <!-- Centraliza os botões -->                
                <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
