<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT * FROM historico WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Uso</title>
</head>
<body>
    <h1>Histórico de Uso</h1>
    <table>
        <tr>
            <th>Ação</th>
            <th>Temperatura</th>
            <th>Data e Hora</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['acao']); ?></td>
            <td><?php echo htmlspecialchars($row['temperatura']); ?></td>
            <td><?php echo htmlspecialchars($row['data_hora']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
