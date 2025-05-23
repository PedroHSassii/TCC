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

// Obter ambientes
$ambientes = [];
$result = $conn->query("SELECT id, nome FROM ambientes");
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
    <title>Cadastro de Código IR</title>
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
                        <option value="<?php echo $ambiente['id']; ?>"><?php echo $ambiente['nome']; ?></option>
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

            <button type="submit" name="ler" class="btn btn-primary">Ler</button>
            <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
