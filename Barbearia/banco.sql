-- Criação do banco de dados
CREATE DATABASE barbearia CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE barbearia;

-- Tabela de usuários
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    sexo ENUM('Masculino', 'Feminino', 'Outro') NOT NULL,
    nome_materno VARCHAR(100) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(15) NOT NULL,
    cep CHAR(8) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    login CHAR(20) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('admin', 'cliente') DEFAULT 'cliente',
    foto VARCHAR(255) NULL
);

-- Tabela de logs
CREATE TABLE logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    acao VARCHAR(50) NOT NULL,
    segundo_fator VARCHAR(50),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  nome_cliente VARCHAR(100) NOT NULL,
  servico VARCHAR(100) NOT NULL,
  data_hora DATETIME NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE two_factor_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code_hash VARCHAR(255) NOT NULL,
    expires_at INT NOT NULL,
    used TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- Usuário administrador padrão (senha deve ser hash depois)
INSERT INTO usuarios 
(nome_completo, data_nascimento, sexo, nome_materno, cpf, email, telefone, cep, endereco, login, senha, perfil)
VALUES
('Administrador Master', '1999-09-04', 'Masculino', 'MaeAdmin', '40028922000', 
'admin@barbearia.com', '+5521912345678', '22000000', 'Rua do Admin, 100, Rio de Janeiro - RJ',
'admin11', '$2y$10$80nVIhPquYCX76xDS9oEv.wn5oJTB0HGGOdD7N5X1odxQIRLEZTBm', 'admin');


-- Nota: A senha acima é um placeholder e deve ser substituída por uma senha segura hash.