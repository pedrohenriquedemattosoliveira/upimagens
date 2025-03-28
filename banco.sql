CREATE DATABASE ModularProduto;
use ModularProduto;

CREATE TABLE produtos(
	id_produto int AUTO_INCREMENT PRIMARY KEY,
	nome_produto varchar(100),
	descricao text
);

CREATE TABLE imagens(
	id_imagem int AUTO_INCREMENT PRIMARY KEY,
	nome_imagem varchar(100),
	fk_id_produto int,
	FOREIGN KEY(fk_id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE
);


