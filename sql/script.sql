-- Criação do banco de dados
CREATE DATABASE sistema_automacao;
USE sistema_automacao;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de ambientes
CREATE TABLE ambientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    numero_sala INT NOT NULL,
    andar INT NOT NULL,
    descricao TEXT NOT NULL,
    usuario_id INT,
    predio_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (predio_id) REFERENCES predios(id) ON DELETE SET NULL
);

-- Tabela de histórico
CREATE TABLE historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    ambiente_id INT,
    acao ENUM('ligar', 'desligar') NOT NULL,
    temperatura INT,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (ambiente_id) REFERENCES ambientes(id) ON DELETE CASCADE
);

-- Usuario Admin
ALTER TABLE usuarios ADD COLUMN is_admin TINYINT(1) DEFAULT 0;

INSERT INTO usuarios (email, senha, is_admin) VALUES ('admin@admin.com', '$2y$10$8EqDWkX96H3..4mAcOa8QeMIVMqL8UgAbeXTcZI2IkthS.M1t63Zy', 1);
INSERT INTO usuarios (email, senha, is_admin) VALUES ('user@user.com', '$2y$10$FrbS5VOYIviJKKNSMJWxaup0wTkMVrYyagK8luY7x5C4H4AI3jLvy');


-- Tabela de prédios
CREATE TABLE predios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    responsavel_id INT,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Adiciona nome ao usuario
ALTER TABLE usuarios 
ADD COLUMN nome VARCHAR(255) NOT NULL;

