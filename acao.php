<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Lê o corpo da requisição
$data = json_decode(file_get_contents("php://input"), true);

// Verifica se a ação foi recebida
if (isset($data['acao']) && isset($data['sala_id'])) {
    $sala_id = $data['sala_id'];
    $acao = $data['acao'];

    // Obtém o status atual do ambiente
    $stmt = $conn->prepare("SELECT status FROM ambientes WHERE id = ?");
    $stmt->bind_param("i", $sala_id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    // Atualiza o status baseado na ação
    switch ($acao) {
        case 'Power':
            // Alterna o status entre 1 (ligado) e 2 (desligado)
            $new_status = ($status === 1) ? 2 : 1;
            break;
        case 'Fan Speed':
            // Aqui você pode implementar a lógica para alterar a velocidade do ventilador
            $new_status = $status; // Exemplo: manter o status atual
            break;
        case 'Temp+':
            // Aqui você pode implementar a lógica para aumentar a temperatura
            $new_status = $status; // Exemplo: manter o status atual
            break;
        case 'Temp−':
            // Aqui você pode implementar a lógica para diminuir a temperatura
            $new_status = $status; // Exemplo: manter o status atual
            break;
        case 'Modo':
            // Aqui você pode implementar a lógica para mudar o modo
            $new_status = $status; // Exemplo: manter o status atual
            break;
        case 'Swing':
            // Aqui você pode implementar a lógica para ativar/desativar o swing
            $new_status = $status; // Exemplo: manter o status atual
            break;
        default:
            echo json_encode(["message" => "Ação não reconhecida."]);
            exit();
    }

    // Atualiza o status no banco de dados
    $stmt = $conn->prepare("UPDATE ambientes SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $sala_id);
    
    if ($stmt->execute()) {
        
        // Aqui você pode enviar um sinal para o Arduino
        // Exemplo: enviar um comando IR
        // irsend.sendNEC(codigo_ir, 32); // Substitua 'codigo_ir' pelo código correspondente
    } else {
        echo json_encode(["message" => "Erro ao executar a ação: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["message" => "Dados insuficientes."]);
}
?>
