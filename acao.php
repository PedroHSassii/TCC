<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona se não estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $acao = $data['acao'];
    $sala_id = $data['sala_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica a ação e atualiza o status
    if ($acao === 'Power') {
        // Obter o status atual da sala
        $stmt = $conn->prepare("SELECT status FROM ambientes WHERE id = ?");
        $stmt->bind_param("i", $sala_id);
        $stmt->execute();
        $stmt->bind_result($status_atual);
        $stmt->fetch();
        $stmt->close();

        // Inverte o status
        $novo_status = $status_atual ? 0 : 1;

        // Atualiza o status no banco de dados
        $stmt = $conn->prepare("UPDATE ambientes SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $novo_status, $sala_id);
        if ($stmt->execute()) {
            echo "Status atualizado com sucesso para " . ($novo_status ? 'Ligado' : 'Desligado');
        } else {
            echo "Erro ao atualizar o status.";
        }
        $stmt->close();
    }

    // Executa a ação de controle do ar-condicionado
    if ($acao == 'ligar') {
        // Enviar sinal IR para ligar o ar-condicionado
        // Exemplo: irsend.sendNEC(0x20DF10EF, 32); // Código do controle remoto
        echo "Ar-condicionado ligado.";
    } elseif ($acao == 'desligar') {
        // Enviar sinal IR para desligar o ar-condicionado
        // Exemplo: irsend.sendNEC(0x20DF10EF, 32); // Código do controle remoto
        echo "Ar-condicionado desligado.";
    }

    // Registrar a ação no banco de dados
    $stmt = $conn->prepare("INSERT INTO historico (usuario_id, ambiente_id, acao, data_hora) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $usuario_id, $sala_id, $acao);
    $stmt->execute();
    $stmt->close();
}
?>
