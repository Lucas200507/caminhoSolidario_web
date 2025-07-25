-- PRECISA CRIAR UMA TABELA DE RELACIONAMENTO dependente e beneficiarios;
CREATE DATABASE caminho_solidario;
USE caminho_solidario;

-- TABELAS
SELECT * FROM pessoa;
SELECT * FROM espera_voluntario;
SELECT * FROM Beneficiario;
SELECT * FROM endereco;
SELECT * FROM filho_dependente;

-- VIEWS
SELECT * FROM tbUsuarios_web;
SELECT * FROM tbBeneficiario;

-- DELETE
DELETE FROM pessoa WHERE idPessoa IN (99);


-- ---------------------------------------------------------------------------------------------------------------------------
		-- ADM / VOLUNTARIO / BENEFICIARIO
-- ---------------------------------------------------------------------------------------------------------------------------
-- TABELAS
-- -------------------------------------------
CREATE TABLE pessoa(
idPessoa INT PRIMARY KEY AUTO_INCREMENT,
nome_completo VARCHAR(100) NOT NULL,
cpf VARCHAR(12) NOT NULL,
telefone VARCHAR(12) NOT NULL);

INSERT INTO pessoa(nome_completo, cpf, telefone) VALUES('teste', '01234567890', '6190000-0000');
INSERT INTO pessoa(nome_completo, cpf, telefone) VALUES('teste2 de tal', '01234567891', '6190000-0000');

-- -------------------------------------------
CREATE TABLE login(
id_user INT PRIMARY KEY auto_increment,
cpf VARCHAR(11) NOT NULL,
senha VARCHAR(60) NOT NULL,
situacao CHAR(1) NOT NULL, -- V - Voluntario / A - Adminstrador
lembrar_senha BIT(1), -- 1 - Verdadeiro / 0 - Falso
idPessoa INT,
FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa) -- RELACIONAMENTO COM PESSOA
);

SELECT * FROM login;

-- ---------------------------------------------

-- DELIMITER

DELIMITER //
CREATE TRIGGER senha_login
BEFORE INSERT 
ON login FOR EACH ROW 
BEGIN
SET NEW.senha = UPPER(MD5(NEW.senha));
END 
//
DELIMITER ;


SELECT * FROM login;
DROP TABLE login;

-- COM TRIGGER
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, 'teste', 'A', 0, 1 FROM pessoa WHERE idPessoa = 1;
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, 'teste2', 'V', 0, 2 FROM pessoa WHERE idPessoa = 2;

-- SEM TRIGGER
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, '698DC19D489C4E4DB73E28A713EAB07B', 'A', 0, 1 FROM pessoa WHERE idPessoa = 1;
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, '38851536D87701D2191990E24A7F8D4E', 'V', 0, 2 FROM pessoa WHERE idPessoa = 2;


-- ---------------------------------------------------------------------------------------------------------------------------
		-- ADM / VOLUNTARIO 
-- ---------------------------------------------------------------------------------------------------------------------------

CREATE TABLE espera_voluntario(
	id_esperaVol INT PRIMARY KEY AUTO_INCREMENT,
    cpf VARCHAR(12) NOT NULL,
    nome_completo VARCHAR(60) NOT NULL,
    email VARCHAR(45) NOT NULL,
    telefone VARCHAR(12) not null,
    estado VARCHAR(7) NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SELECT * FROM espera_voluntario;


-- -----------------------------------------
-- LUCAS: Estou usando essa tabela ao invés de voluntario e adm
CREATE TABLE usuarios_web(
	idUsuariosWeb INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) not null,
	data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	idPessoa INT NOT NULL,
	FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa)
);

INSERT INTO usuarios_web(email,idPessoa) VALUES("teste@gmail.com",1);
INSERT INTO usuarios_web(email,idPessoa) VALUES("teste2@gmail.com",2);

--  ------------------------------------------
-- VIEW

CREATE VIEW tbUsuarios_web AS
SELECT
u.idUsuariosWeb AS ID,
p.cpf,
p.nome_completo AS usuario,
u.email,
p.telefone,
l.senha AS senha,
l.situacao,
l.lembrar_senha,
u.data_cadastro
FROM usuarios_web u
INNER JOIN pessoa p ON p.idPessoa = u.idPessoa
INNER JOIN login l ON l.idPessoa = u.idPessoa;

SELECT * FROM tbUsuarios_web;
-- ---------------------------------------------------------------------------------------------------------------------------
		-- DEPENDENTES E BENEFICIARIOS
-- ---------------------------------------------------------------------------------------------------------------------------
-- ENDERECO PARA BENEFICIARIOS / DEPENDENTES
CREATE TABLE endereco(
idEndereco INT PRIMARY KEY AUTO_INCREMENT,
endereco VARCHAR(100) not null,
cidade VARCHAR(50) not null,
estado CHAR(2) NOT NULL,
cep VARCHAR(8) NOT NULL,
situacao_moradia CHAR(1) NOT NULL,
valor_despesas FLOAT(10),
idPessoa Int References pessoa (idPessoa),
foreign key (idPessoa) references pessoa (idPessoa));

SELECT * FROM endereco;

-- --------------------------------------
CREATE TABLE nomeBeneficiosGov(
    idBeneficios_gov INT PRIMARY KEY AUTO_INCREMENT,
    nome_beneficiogov VARCHAR(75) NOT NULL
);

INSERT INTO nomeBeneficiosGov (nome_beneficiogov) VALUES ("Novo Bolsa Família"), ("Benefício de Prestação Continuada (BPC)"), ("Aposentadoria"), ("Vale-Gas"), ("Outros");
-- --------------------------------------------

Create TABLE BeneficioGov(
idBeneficioGov INT PRIMARY KEY AUTO_INCREMENT,
idBeneficios_gov INT NOT NULL,
FOREIGN KEY (idBeneficios_gov) REFERENCES nomeBeneficiosGov (idBeneficios_gov),
valor_beneficio FLOAT(10));

SELECT * FROM BeneficioGov;
-- --------------------------------------------

CREATE TABLE Beneficiario (
    idBeneficiario INT PRIMARY KEY AUTO_INCREMENT,
    data_nascimento DATE NOT NULL,
    email VARCHAR(50),
    estado_civil CHAR(1) NOT NULL,
    PCD CHAR(1) NOT NULL,
    laudo CHAR(1), -- CASO PCD, possui laudo ou não (s/n)
    doenca VARCHAR(50),
    quantos_dependentes INT,
    qnt_trabalham INT NOT NULL,
    renda_familiar FLOAT(10),
    idPessoa INT,
    idEndereco INT,
    idBeneficioGov INT,
    FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa),
    FOREIGN KEY (idEndereco) REFERENCES endereco (idEndereco),
    FOREIGN KEY (idBeneficioGov) REFERENCES BeneficioGov (idBeneficioGov)
);


SELECT * FROM Beneficiario;

-- -------------------------------------------------------------------------------------

CREATE VIEW tbBeneficiario AS
SELECT 
bfc.idBeneficiario AS ID,
p.cpf,
p.nome_completo AS nome,
bfc.estado_civil,
bfc.PCD,
bfc.quantos_dependentes,
nbg.nome_beneficiogov AS beneficioGov,
e.cep
FROM Beneficiario bfc
INNER JOIN endereco e ON e.idEndereco = bfc.idEndereco
INNER JOIN pessoa p ON p.idPessoa = bfc.idPessoa
LEFT JOIN BeneficioGov bg ON bg.idBeneficioGov = bfc.idBeneficioGov -- O LEFT JOIN retorna todas as linhas da tabela Beneficiario mesmo que não haja correspondência na tabela BeneficioGov.
LEFT JOIN nomeBeneficiosGov nbg ON nbg.idBeneficios_gov = bg.idBeneficios_gov;

SELECT * FROM tbBeneficiario;


-- --------------------------------------------

CREATE TABLE beneficio(
idBeneficio INT PRIMARY KEY AUTO_INCREMENT,
data_cadastro DATE not null,
data_entrada DATE,
data_saida DATE,
prorrogacao CHAR(1),
duracao VARCHAR(10),
situacao_beneficio CHAR(1) NOT NULL,
idUsuario INT, -- Melhor fazer o contrário, o usuario ter o idBeneficio
FOREIGN KEY (idUsuario) REFERENCES Usuario (idUsuario));

SELECT * FROM beneficio;
DROP TABLE endereco;

-- -------------------------------------------
-- PRECISA CRIAR UMA TABELA DE RELACIONAMENTO dependente e beneficiarios;
CREATE DATABASE caminho_solidario;
USE caminho_solidario;

-- TABELAS
SELECT * FROM pessoa;
SELECT * FROM espera_voluntario;
SELECT * FROM Beneficiario;
SELECT * FROM endereco;
SELECT * FROM filho_dependente;

-- VIEWS
SELECT * FROM tbUsuarios_web;
SELECT * FROM tbBeneficiario;
SELECT * FROM tbRelatorio;

-- DELETE
DELETE FROM pessoa WHERE idPessoa IN (99);


-- ---------------------------------------------------------------------------------------------------------------------------
		-- ADM / VOLUNTARIO / BENEFICIARIO
-- ---------------------------------------------------------------------------------------------------------------------------
-- TABELAS
-- -------------------------------------------
CREATE TABLE pessoa(
idPessoa INT PRIMARY KEY AUTO_INCREMENT,
nome_completo VARCHAR(100) NOT NULL,
cpf VARCHAR(12) NOT NULL,
telefone VARCHAR(12) NOT NULL);

INSERT INTO pessoa(nome_completo, cpf, telefone) VALUES('teste', '01234567890', '6190000-0000');
INSERT INTO pessoa(nome_completo, cpf, telefone) VALUES('teste2 de tal', '01234567891', '6190000-0000');

-- -------------------------------------------
CREATE TABLE login(
id_user INT PRIMARY KEY auto_increment,
cpf VARCHAR(11) NOT NULL,
senha VARCHAR(60) NOT NULL,
situacao CHAR(1) NOT NULL, -- V - Voluntario / A - Adminstrador
lembrar_senha BIT(1), -- 1 - Verdadeiro / 0 - Falso
idPessoa INT,
FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa) -- RELACIONAMENTO COM PESSOA
);

SELECT * FROM login;

-- ---------------------------------------------

-- DELIMITER

DELIMITER //
CREATE TRIGGER senha_login
BEFORE INSERT 
ON login FOR EACH ROW 
BEGIN
SET NEW.senha = UPPER(MD5(NEW.senha));
END 
//
DELIMITER ;


SELECT * FROM login;
DROP TABLE login;

-- COM TRIGGER
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, 'teste', 'A', 0, 1 FROM pessoa WHERE idPessoa = 1;
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, 'teste2', 'V', 0, 2 FROM pessoa WHERE idPessoa = 2;

-- SEM TRIGGER
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, '698DC19D489C4E4DB73E28A713EAB07B', 'A', 0, 1 FROM pessoa WHERE idPessoa = 1;
INSERT INTO login (cpf, senha, situacao, lembrar_senha, idPessoa) SELECT cpf, '38851536D87701D2191990E24A7F8D4E', 'V', 0, 2 FROM pessoa WHERE idPessoa = 2;


-- ---------------------------------------------------------------------------------------------------------------------------
		-- ADM / VOLUNTARIO 
-- ---------------------------------------------------------------------------------------------------------------------------

CREATE TABLE espera_voluntario(
	id_esperaVol INT PRIMARY KEY AUTO_INCREMENT,
    cpf VARCHAR(12) NOT NULL,
    nome_completo VARCHAR(60) NOT NULL,
    email VARCHAR(45) NOT NULL,
    telefone VARCHAR(12) not null,
    estado VARCHAR(7) NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SELECT * FROM espera_voluntario;


-- -----------------------------------------
-- LUCAS: Estou usando essa tabela ao invés de voluntario e adm
CREATE TABLE usuarios_web(
	idUsuariosWeb INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) not null,
	data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	idPessoa INT NOT NULL,
	FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa)
);

INSERT INTO usuarios_web(email,idPessoa) VALUES("teste@gmail.com",1);
INSERT INTO usuarios_web(email,idPessoa) VALUES("teste2@gmail.com",2);

--  ------------------------------------------
-- VIEW

CREATE VIEW tbUsuarios_web AS
SELECT
u.idUsuariosWeb AS ID,
p.cpf,
p.nome_completo AS usuario,
u.email,
p.telefone,
l.senha AS senha,
l.situacao,
l.lembrar_senha,
u.data_cadastro
FROM usuarios_web u
INNER JOIN pessoa p ON p.idPessoa = u.idPessoa
INNER JOIN login l ON l.idPessoa = u.idPessoa;

SELECT * FROM tbUsuarios_web;
-- ---------------------------------------------------------------------------------------------------------------------------
		-- DEPENDENTES E BENEFICIARIOS
-- ---------------------------------------------------------------------------------------------------------------------------
-- ENDERECO PARA BENEFICIARIOS / DEPENDENTES
CREATE TABLE endereco(
idEndereco INT PRIMARY KEY AUTO_INCREMENT,
endereco VARCHAR(100) not null,
cidade VARCHAR(50) not null,
estado CHAR(2) NOT NULL,
cep VARCHAR(8) NOT NULL,
situacao_moradia CHAR(1) NOT NULL,
valor_despesas FLOAT(10),
idPessoa Int References pessoa (idPessoa),
foreign key (idPessoa) references pessoa (idPessoa));

SELECT * FROM endereco;

-- --------------------------------------
CREATE TABLE nomeBeneficiosGov(
    idBeneficios_gov INT PRIMARY KEY AUTO_INCREMENT,
    nome_beneficiogov VARCHAR(75) NOT NULL
);

INSERT INTO nomeBeneficiosGov (nome_beneficiogov) VALUES ("Novo Bolsa Família"), ("Benefício de Prestação Continuada (BPC)"), ("Aposentadoria"), ("Vale-Gas"), ("Outros");
-- --------------------------------------------

Create TABLE BeneficioGov(
idBeneficioGov INT PRIMARY KEY AUTO_INCREMENT,
idBeneficios_gov INT NOT NULL,
FOREIGN KEY (idBeneficios_gov) REFERENCES nomeBeneficiosGov (idBeneficios_gov),
valor_beneficio FLOAT(10));

SELECT * FROM BeneficioGov;
-- --------------------------------------------

CREATE TABLE Beneficiario (
    idBeneficiario INT PRIMARY KEY AUTO_INCREMENT,
    data_nascimento DATE NOT NULL,
    email VARCHAR(50),
    estado_civil CHAR(1) NOT NULL,
    PCD CHAR(1) NOT NULL,
    laudo CHAR(1), -- CASO PCD, possui laudo ou não (s/n)
    doenca VARCHAR(50),
    quantos_dependentes INT,
    qnt_trabalham INT NOT NULL,
    renda_familiar FLOAT(10),
    idPessoa INT,
    idEndereco INT,
    idBeneficioGov INT,
    FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa),
    FOREIGN KEY (idEndereco) REFERENCES endereco (idEndereco),
    FOREIGN KEY (idBeneficioGov) REFERENCES BeneficioGov (idBeneficioGov)
);


SELECT * FROM Beneficiario;

-- -------------------------------------------------------------------------------------

CREATE VIEW tbBeneficiario AS
SELECT 
bfc.idBeneficiario AS ID,
p.cpf,
p.nome_completo AS nome,
bfc.estado_civil,
bfc.PCD,
bfc.quantos_dependentes,
nbg.nome_beneficiogov AS beneficioGov,
e.cep
FROM Beneficiario bfc
INNER JOIN endereco e ON e.idEndereco = bfc.idEndereco
INNER JOIN pessoa p ON p.idPessoa = bfc.idPessoa
LEFT JOIN BeneficioGov bg ON bg.idBeneficioGov = bfc.idBeneficioGov -- O LEFT JOIN retorna todas as linhas da tabela Beneficiario mesmo que não haja correspondência na tabela BeneficioGov.
LEFT JOIN nomeBeneficiosGov nbg ON nbg.idBeneficios_gov = bg.idBeneficios_gov;

SELECT * FROM tbBeneficiario;


-- --------------------------------------------

CREATE TABLE beneficio(
idBeneficio INT PRIMARY KEY AUTO_INCREMENT,
data_cadastro DATE not null,
data_entrada DATE,
data_saida DATE,
prorrogacao CHAR(1),
duracao VARCHAR(10),
situacao_beneficio CHAR(1) NOT NULL,
idUsuario INT, -- Melhor fazer o contrário, o usuario ter o idBeneficio
FOREIGN KEY (idUsuario) REFERENCES Usuario (idUsuario));

SELECT * FROM beneficio;
DROP TABLE endereco;

-- -------------------------------------------

CREATE TABLE frequencia (
idFrequencia int primary key auto_increment,
ANO CHAR (5),
MES VARCHAR (15),
REGISTRO CHAR(1), -- Presente / Falta
idBeneficiario INT NOT NULL,
FOREIGN KEY (idBeneficiario) references Beneficiario (idBeneficiario));

SELECT * FROM frequencia;

-- -------------------------------------------

CREATE VIEW tbRelatorio AS
SELECT 
	f.idFrequencia AS ID,
    p.nome_completo AS nome,
    p.cpf,
    f.ANO,
    f.MES,
    f.REGISTRO
FROM frequencia f
INNER JOIN Beneficiario bfc ON bfc.idBeneficiario = f.idBeneficiario
INNER JOIN Pessoa p ON p.idPessoa = bfc.idPessoa;

SELECT * FROM tbRelatorio;

-- -------------------------------------------------

CREATE TABLE filho_dependente(
idFilho_Dependente INT PRIMARY KEY AUTO_INCREMENT,
nome_filho_dependente VARCHAR(50),
cpf VARCHAR(12) NOT NULL,
data_nascimento_filho_dep DATE,
parentesco VARCHAR(10),
PCD CHAR(1) NOT NULL,
laudo CHAR(1), -- CASO PCD, possui laudo ou não (s/n)
doenca VARCHAR(50),
idBeneficiario INT NOT NULL,
FOREIGN KEY (idBeneficiario) references Beneficiario (idBeneficiario));

SELECT * FROM filho_dependente;

-- --------------------------------------------
		-- TEM QUE FAZER A TABELA DE RELACIONAMENTO dependente e beneficiario
-- --------------------------------------------
-- VIEWS 

CREATE VIEW filho_dependente_beneficiario AS
SELECT 
	fd.idFilho_Dependente,
    fd.nome_filho_dependente AS nome,
    fd.data_nascimento_filho_dep AS data_nascimento,
    fd.parentesco,
    fd.pcd,
    p.nome_completo AS beneficiario
FROM filho_dependente fd
INNER JOIN beneficiario b ON fd.idBeneficiario = b.idBeneficiario
INNER JOIN pessoa p ON p.idPessoa = b.idPessoa;

-- ------------------------------------------

-- Isso evita que o banco envie valores repetidos --
 
SELECT DISTINCT tipo FROM funcao;
SELECT DISTINCT nome_completo FROM pessoa;

-- Para buscar apenas os meses com registro "P" ou "F"

SELECT r.idRelatorio, f.registro, r.mes  
FROM relatorio r 
INNER JOIN frequencia f ON r.mes = f.mes 
WHERE f.registro = 'F'; -- Ou 'F' para faltas

-- ----------------------------------------------
