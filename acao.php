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

    // Atualiza a velocidade do ventilador
    if ($acao === 'Fan Speed') {
        $nova_velocidade = $data['velocidade']; // Recebe a nova velocidade
        $stmt = $conn->prepare("UPDATE ambientes SET velocidade = ? WHERE id = ?");
        $stmt->bind_param("ii", $nova_velocidade, $sala_id);
        if ($stmt->execute()) {
            echo "Velocidade do ventilador atualizada para " . $nova_velocidade;
        } else {
            echo "Erro ao atualizar a velocidade do ventilador.";
        }
        $stmt->close();
    }

    // Atualiza a temperatura
    if ($acao === 'Temp+') {
        $stmt = $conn->prepare("UPDATE ambientes SET temperatura = temperatura + 1 WHERE id = ?");
        $stmt->bind_param("i", $sala_id);
        if ($stmt->execute()) {
            echo "Temperatura aumentada em 1 grau.";
        } else {
            echo "Erro ao aumentar a temperatura.";
        }
        $stmt->close();
    } elseif ($acao === 'Temp−') {
        $stmt = $conn->prepare("UPDATE ambientes SET temperatura = temperatura - 1 WHERE id = ?");
        $stmt->bind_param("i", $sala_id);
        if ($stmt->execute()) {
            echo "Temperatura diminuída em 1 grau.";
        } else {
            echo "Erro ao diminuir a temperatura.";
        }
        $stmt->close();
    }

    // Atualiza o modo
    if ($acao === 'Modo') {
        $novo_modo = $data['modo']; // Recebe o novo modo
        $stmt = $conn->prepare("UPDATE ambientes SET modo = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_modo, $sala_id);
        if ($stmt->execute()) {
            echo "Modo atualizado para " . htmlspecialchars($novo_modo);
        } else {
            echo "Erro ao atualizar o modo.";
        }
        $stmt->close();
    }

    // Registrar a ação no banco de dados
    $stmt = $conn->prepare("INSERT INTO historico (usuario_id, ambiente_id, acao, data_hora) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $usuario_id, $sala_id, $acao);
    $stmt->execute();
    $stmt->close();
}
?>
