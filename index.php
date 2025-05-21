<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sistema de Automação e Controle de Temperatura</title>
</head>
<body>
    <header>
        <h1>Bem-vindo ao Sistema de Automação e Controle de Temperatura!</h1>
    </header>
    
    <main>
        <section>
            <h2>Descrição do Sistema</h2>
            <p>
                Este sistema permite o controle remoto de ar-condicionado através de uma interface web. 
                Você pode ligar ou desligar o ar-condicionado, ajustar a temperatura e monitorar o histórico de uso.
            </p>
            <p>
                O sistema utiliza um microcontrolador ESP32 para enviar comandos infravermelhos para o ar-condicionado, 
                além de monitorar a temperatura ambiente com um sensor DHT22.
            </p>
        </section>

        <section>
            <h2>Funcionalidades</h2>
            <ul>
                <li><a href="login.php">Login</a> - Acesse sua conta para controlar o ar-condicionado.</li>
                <li><a href="cadastrar_ambiente.php">Cadastrar Ambiente</a> - Adicione novos ambientes para controle.</li>
                <li><a href="controle.php">Controle do Ar-Condicionado</a> - Ligue, desligue e ajuste a temperatura.</li>
                <li><a href="historico.php">Histórico de Uso</a> - Veja o histórico de ações realizadas.</li>
            </ul>
        </section>

        <section>
            <h2>Como Funciona</h2>
            <p>
                Após fazer login, você poderá selecionar o ambiente que deseja controlar. 
                O sistema permite que você ajuste a temperatura e ligue ou desligue o ar-condicionado conforme necessário. 
                Todas as ações são registradas para que você possa acompanhar o uso do sistema.
            </p>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Sistema de Automação e Controle de Temperatura. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
