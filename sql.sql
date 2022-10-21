create database sistema;

create table usuarios(
    id BIGINT NOT NULL AUTO_INCREMENT,
    nome_completo char(255),
    endereco char(255),
    email char(255),
    senha char(255),
    PRIMARY KEY (id)
)

insert into usuarios values (default, 'admin','', 'admin@admin.com','21232f297a57a5a743894a0e4a801fc3')