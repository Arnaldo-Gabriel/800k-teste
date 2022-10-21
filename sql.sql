create database sistema;

create table usuarios(
    id BIGINT NOT NULL AUTO_INCREMENT,
    nome_completo char(255),
    endereco char(255),
    email char(255),
    senha char(255),
    PRIMARY KEY (id)
)