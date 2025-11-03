DROP DATABASE IF EXISTS barbearia;
CREATE DATABASE barbearia CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE barbearia;

-- ============================
-- TABELA: USUÁRIOS
-- ============================

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    sexo VARCHAR(15) NOT NULL,
    nome_materno VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone_celular VARCHAR(20),
    telefone_fixo VARCHAR(20),
    cep VARCHAR(10) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    login VARCHAR(30) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('admin', 'cliente') DEFAULT 'cliente',
    foto VARCHAR(255) NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    UNIQUE (cpf),
    UNIQUE (login),
    UNIQUE (email)
);

-- ============================
-- TABELA: LOGS
-- ============================

CREATE TABLE logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    acao VARCHAR(50) NOT NULL,
    segundo_fator VARCHAR(50),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================
-- TABELA: AGENDAMENTOS
-- ============================

CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nome_cliente VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL, 
    servico VARCHAR(100) NOT NULL,
    data_hora DATETIME NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================
-- TABELA: CÓDIGOS 2FA
-- ============================

CREATE TABLE two_factor_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code_hash VARCHAR(255) NOT NULL,
    expires_at INT NOT NULL,
    used TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- ============================
-- USUÁRIO ADMIN PADRÃO
-- O campo 'data_cadastro' será preenchido automaticamente com o timestamp atual.
-- ============================

INSERT INTO usuarios 
(nome_completo, data_nascimento, sexo, nome_materno, cpf, email, telefone_celular, telefone_fixo, cep, endereco, login, senha, perfil)
VALUES
('Administrador Master', '1999-09-04', 'Masculino', 'MaeAdmin', '40028922000',
'admin@barbearia.com', '+5521912345678', '+5521987654321', '22000000',
'Rua do Admin, 100, Rio de Janeiro - RJ',
'admin11', '$2y$10$80nVIhPquYCX76xDS9oEv.wn5oJTB0HGGOdD7N5X1odxQIRLEZTBm', 'admin');