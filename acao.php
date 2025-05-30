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

    // Atualiza o status no banco de dados
    $stmt = $conn->prepare("UPDATE ambientes SET status = ? WHERE id = ?");
    
    // Define o status baseado na ação
    switch ($acao) {
        case 'Power':
            $status = 1; // Ligar
            break;
        case 'Fan Speed':
            // Aqui você pode implementar a lógica para alterar a velocidade do ventilador
            $status = 2; // Exemplo: mudar a velocidade
            break;
        case 'Temp+':
            // Aqui você pode implementar a lógica para aumentar a temperatura
            $status = 3; // Exemplo: aumentar temperatura
            break;
        case 'Temp−':
            // Aqui você pode implementar a lógica para diminuir a temperatura
            $status = 4; // Exemplo: diminuir temperatura
            break;
        case 'Modo':
            // Aqui você pode implementar a lógica para mudar o modo
            $status = 5; // Exemplo: mudar modo
            break;
        case 'Swing':
            // Aqui você pode implementar a lógica para ativar/desativar o swing
            $status = 6; // Exemplo: ativar swing
            break;
        default:
            echo "Ação não reconhecida.";
            exit();
    }

    // Executa a atualização
    $stmt->bind_param("ii", $status, $sala_id);
    if ($stmt->execute()) {
        echo "Ação executada com sucesso!";
        
        // Aqui você pode enviar um sinal para o Arduino
        // Exemplo: enviar um comando IR
        // irsend.sendNEC(codigo_ir, 32); // Substitua 'codigo_ir' pelo código correspondente
    } else {
        echo "Erro ao executar a ação: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Dados insuficientes.";
}
?>
