CREATE DATABASE db_manifesto;
USE db_manifesto;
 
CREATE TABLE tb_categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ctgNome VARCHAR(100) NOT NULL
);
 
CREATE TABLE tb_colecao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    colecaoNome VARCHAR(100) NOT NULL
);
 
CREATE TABLE tb_usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomeUser VARCHAR(180) NOT NULL,
    email VARCHAR(180) NOT NULL,
    senha VARCHAR(180) NOT NULL,
    cpf VARCHAR(180) NOT NULL,
    dataNascimento DATE,
    telefone VARCHAR(20),
    statusATV ENUM('ativo', 'desativado') NOT NULL DEFAULT 'ativo',
    tipoUser ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);
 
CREATE TABLE tb_endereco (
    idEndereco INT PRIMARY KEY AUTO_INCREMENT,
    apelidoEndereco VARCHAR(80),
    cep VARCHAR(9) NOT NULL,
    rua VARCHAR(80) NOT NULL,
    numero VARCHAR(30) NOT NULL,
    bairro VARCHAR(80) NOT NULL,
    cidade VARCHAR(80) NOT NULL,
    complemento VARCHAR(80),
    idUsuario INT NOT NULL,
    CONSTRAINT fk_endereco_usuario FOREIGN KEY (idUsuario) REFERENCES tb_usuario (id)
);
 
CREATE TABLE tb_produto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomeItem VARCHAR(180) NOT NULL,
    descItem TEXT NOT NULL,
    valorItem DECIMAL(10, 2) NOT NULL,
    estoqueItem INT NOT NULL DEFAULT 0,
    idCategoria INT NOT NULL,
    idColecao INT NOT NULL,
    CONSTRAINT fk_produto_categoria FOREIGN KEY (idCategoria) REFERENCES tb_categoria (id),
    CONSTRAINT fk_produto_colecao FOREIGN KEY (idColecao) REFERENCES tb_colecao (id)
);
 
CREATE TABLE tb_imagem (
    idImagem INT PRIMARY KEY AUTO_INCREMENT,
    nomeImagem VARCHAR(80) NOT NULL DEFAULT 'Imagem',
    statusImagem ENUM('inativa', 'ativa', 'principal') NOT NULL DEFAULT 'inativa',
    idProduto INT,
    CONSTRAINT fk_imagem_produto FOREIGN KEY (idProduto) REFERENCES tb_produto (id)
);
 
CREATE TABLE tb_compra (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dataCompra TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statusCompra ENUM('Processando pagamento', 'Pago', 'Preparando pra enviar', 'Enviado', 'Recebido') NOT NULL DEFAULT 'Processando pagamento',
    idUsuario INT,
    CONSTRAINT fk_compra_usuario FOREIGN KEY (idUsuario) REFERENCES tb_usuario (id)
);
 
CREATE TABLE tb_itemCompra (
    idCompra INT,
    idProduto INT,
    quantidade INT NOT NULL DEFAULT 1,
    valorUnitario DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (idCompra, idProduto),
    CONSTRAINT fk_item_compra FOREIGN KEY (idCompra) REFERENCES tb_compra (id),
    CONSTRAINT fk_item_produto FOREIGN KEY (idProduto) REFERENCES tb_produto (id)
);
 
CREATE TABLE tb_pagamento (
    idPagamento INT PRIMARY KEY AUTO_INCREMENT,
    statusPagamento ENUM('Processando', 'Pago', 'Negado') NOT NULL DEFAULT 'Processando',
    tipoPagamento ENUM('PIX', 'Boleto', 'Cartão Crédito', 'Cartão Débito', 'Paypal') NOT NULL,
    compraid INT,
    CONSTRAINT fk_pagamento_compra FOREIGN KEY (compraid) REFERENCES tb_compra(id)
);
 
CREATE TABLE tb_novidades (
    idNovidade INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(180) NOT NULL,
    dataNotivade DATE NOT NULL,
    imagemNovidade VARCHAR(180) NOT NULL,
    conteudo TEXT NOT NULL
);
 
CREATE TABLE tb_cupom (
    idCupom INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    porcentagemDesconto INT NOT NULL,
    quantidadeUso INT DEFAULT 1,
    utilizados INT DEFAULT 0,
    valorCompraMin DECIMAL(10,2) DEFAULT 0.00,
    dataValidade DATE,
    statusCupom ENUM('ativo', 'desativado') DEFAULT 'ativo',
    descricaoCupom TEXT
);
 
 