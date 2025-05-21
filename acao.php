<?php
session_start();
include 'db.php'; // Inclui o arquivo de conex�o com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona se n�o estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'];
    $temperatura = $_POST['temperatura'];
    $usuario_id = $_SESSION['usuario_id'];
    $ambiente_id = 1; // Defina o ID do ambiente conforme necess�rio

    // Executa a a��o de controle
    if ($acao == 'ligar') {
        // Enviar sinal IR para ligar o ar-condicionado
        // Exemplo: irsend.sendNEC(0x20DF10EF, 32); // C�digo do controle remoto
        echo "Ar-condicionado ligado a " . $temperatura . " graus.";
    } elseif ($acao == 'desligar') {
        // Enviar sinal IR para desligar o ar-condicionado
        // Exemplo: irsend.sendNEC(0x20DF10EF, 32); // C�digo do controle remoto
        echo "Ar-condicionado desligado.";
    }

    // Registrar a a��o no banco de dados
    $stmt = $conn->prepare("INSERT INTO historico (usuario_id, ambiente_id, acao, temperatura, data_hora) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisi", $usuario_id, $ambiente_id, $acao, $temperatura);
    $stmt->execute();
    $stmt->close();
}
?>
