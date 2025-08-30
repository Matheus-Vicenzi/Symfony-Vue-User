-- Script de inicialização do banco de dados
CREATE DATABASE IF NOT EXISTS symfony_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Garantir que o usuário tenha as permissões corretas
GRANT ALL PRIVILEGES ON symfony_db.* TO 'symfony_user'@'%';
FLUSH PRIVILEGES;
