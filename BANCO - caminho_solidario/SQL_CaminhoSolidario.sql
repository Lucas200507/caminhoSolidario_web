CREATE DATABASE caminho_solidario;
USE caminho_solidario;

-- TABELAS
-- EU COLOCARIA A SITUAÇÃO: VOLUNTÁRIO, ADMINISTRADOR OU DEPENDENTE
CREATE TABLE login(
id_user INT PRIMARY KEY auto_increment,
cpf VARCHAR(11) NOT NULL,
senha VARCHAR(60) NOT NULL,
situacao CHAR(1) NOT NULL, -- V - Voluntario / A - Adminstrador
idPessoa INT,
FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa) -- RELACIONAMENTO COM PESSOA
);


INSERT INTO login (cpf, senha, situacao, idPessoa) SELECT cpf, 'teste', 'A', 1 FROM pessoa WHERE idPessoa = 1;
INSERT INTO login (cpf, senha, situacao, idPessoa) SELECT cpf, 'teste2', 'V', 2 FROM pessoa WHERE idPessoa = 2;


SELECT * FROM login;
SELECT * FROM tbUsuarios_web;
-- TEMOS QUE FAZER O RELACIONAMENTO ENTRE LOGIN E PESSOA
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

-- --------------------------------------------

CREATE TABLE espera_voluntario(
	id_esperaVol INT PRIMARY KEY AUTO_INCREMENT,
    cpf VARCHAR(12) NOT NULL,
    nome_completo VARCHAR(60) NOT NULL,
    email VARCHAR(45) NOT NULL,
    telefone VARCHAR(12) not null,
    data_pedido DATE,
    id_enderecoV INT,
    FOREIGN KEY (id_enderecoV) REFERENCES endereco_voluntario (id_enderecoV)
);

SELECT * FROM espera_voluntario;
DROP TABLE espera_voluntario;

-- -------------------------------------------
CREATE TABLE pessoa(
idPessoa INT PRIMARY KEY AUTO_INCREMENT,
nome_completo VARCHAR(100) NOT NULL,
cpf VARCHAR(12) NOT NULL,
telefone VARCHAR(12) NOT NULL);


INSERT INTO pessoa(nome_completo, cpf, telefone) VALUES('teste', '01234567890', '6190000-0000');
INSERT INTO pessoa(nome_completo, cpf, telefone) VALUES('teste2 de tal', '01234567891', '6190000-0000');


SELECT * FROM pessoa;
DROP TABLE pessoa;

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


-- --------------------------------------------
-- NÃO VEJO NECESSIDADE, PODERIAMOS USAR A VIEW tbVoluntario e definir apenas sua situação
CREATE TABLE adm(
idAdm INT PRIMARY KEY AUTO_INCREMENT,
email_adm VARCHAR(50) not null,
data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
idPessoa INT NOT NULL,
FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa));

SELECT * FROM adm;
DROP TABLE adm;

INSERT INTO adm(email_adm,idPessoa) VALUES("teste@gmail.com",1);
-- --------------------------------------------
-- Para a tela Usuario
CREATE TABLE voluntario (
    idVoluntarios INT PRIMARY KEY AUTO_INCREMENT,
    email_voluntario VARCHAR(50) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idPessoa INT NOT NULL,
    FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa)
);


INSERT INTO voluntario(email_voluntario,idPessoa) VALUES("joaogomes@gmail.com",1);


SELECT * FROM voluntario;
DROP TABLE voluntario;

-- ---------------------------------------------

CREATE TABLE funcao(
    idFuncao INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(25) NOT NULL,
    email VARCHAR(60) NOT NULL,
    senha VARCHAR(50) NOT NULL,
    idPessoa INT REFERENCES pessoa (idPessoa),
    FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa));
    
    SELECT * FROM funcao;
    DROP TABLE funcao;
     
	-- INSERÇÃO NA TABELA
    INSERT INTO funcao(tipo,email,senha,idPessoa) VALUES("Voluntário","joaogomes@gmail.com","123",1);

-- ---------------------------------------------

CREATE TABLE endereco(
idEndereco INT PRIMARY KEY AUTO_INCREMENT,
endereco VARCHAR(50) not null,
bairro VARCHAR(50),
cidade VARCHAR(50) not null,
cep VARCHAR(8) NOT NULL,
situacao_moradia CHAR(1) NOT NULL,
valor_aluguel FLOAT(10),
idPessoa Int References pessoa (idPessoa),
foreign key (idPessoa) references pessoa (idPessoa));

SELECT * FROM endereco;
DROP TABLE endereco;

-- --------------------------------------------

CREATE TABLE filho_dependente(
idFilho_Dependente INT PRIMARY KEY AUTO_INCREMENT,
nome_filho_dependente VARCHAR(50),
data_nascimento_filho_dep DATE,
parentesco VARCHAR(10),
pcd CHAR(1),
idEndereco INT REFERENCES endereco (idEndereco),
foreign key (idEndereco) references endereco (idEndereco));

SELECT * FROM filho_dependente;
DROP TABLE filho_dependente;

-- --------------------------------------------

Create TABLE BeneficioGov(
idBeneficioGov INT PRIMARY KEY AUTO_INCREMENT,
possui_beneficio CHAR(1) NOT NULL,
nome_beneficio_gov VARCHAR(20),
valor_beneficio FLOAT(10));

SELECT * FROM BeneficioGov;
DROP TABLE BeneficioGov;

-- --------------------------------------------

CREATE TABLE Beneficiario(
idBeneficiario INT PRIMARY KEY AUTO_INCREMENT,
data_nascimento_usuario DATE NOT NULL,
estado_civil CHAR(1) NOT NULL,
renda_familiar FLOAT(10),
idPessoa INT,
FOREIGN KEY (idPessoa) REFERENCES pessoa (idPessoa),
idFilho_Dependente INT,
FOREIGN KEY (idFilho_Dependente) REFERENCES filho_dependente (idFilho_Dependente),
idEndereco INT,
FOREIGN KEY (idEndereco) REFERENCES endereco (idEndereco),
idBeneficioGov INT,
FOREIGN KEY (idBeneficioGov) REFERENCES BeneficioGov (idBeneficioGov));

SELECT * FROM Beneficiario;
DROP TABLE Beneficiario;

-- --------------------------------------------

CREATE TABLE beneficio(
idBeneficio INT PRIMARY KEY AUTO_INCREMENT,
data_cadastro DATE not null,
data_entrada DATE,
data_saida DATE,
prorrogacao CHAR(1),
duracao VARCHAR(10),
situacao_beneficio CHAR(1) NOT NULL,
idUsuario INT, 
FOREIGN KEY (idUsuario) REFERENCES Usuario (idUsuario));

SELECT * FROM beneficio;
DROP TABLE endereco;

-- -------------------------------------------

CREATE TABLE frequencia (
idFrequencia int primary key auto_increment,
CPF VARCHAR (15),
ANO CHAR (5),
MES VARCHAR (15),
REGISTRO CHAR(1)
);

SELECT * FROM frequencia;
DROP TABLE frequencia;

-- -------------------------------------------

CREATE TABLE relatorio(
idRelatorio int primary key auto_increment,
CPF VARCHAR (15),
ANO CHAR (5),
REGISTRO CHAR(1),
idFrequencia INT, 
FOREIGN KEY (idFrequencia) REFERENCES frequencia (idFrequencia)
);

SELECT * FROM relatorio;
DROP TABLE relatorio;

-- ----------------------------------------

CREATE TABLE endereco_voluntario(
id_enderecoV INT PRIMARY KEY AUTO_INCREMENT,
cep VARCHAR(9),
cidade VARCHAR(45),
bairro VARCHAR(45),
endereco VARCHAR(45)
);

SELECT * FROM endereco_voluntario;
DROP TABLE endereco_voluntario;

-- ----------------------------------------------

-- VIEWS 

CREATE VIEW tbUsuarios_web AS
SELECT

u.idUsuariosWeb AS ID,
p.cpf,
p.nome_completo AS usuario,
u.email,
p.telefone,
l.senha AS senha,
l.situacao,
u.data_cadastro
FROM usuarios_web u
INNER JOIN pessoa p ON p.idPessoa = u.idPessoa
INNER JOIN login l ON l.idPessoa = u.idPessoa;


SELECT * FROM tbUsuarios_web;

-- ------

CREATE VIEW tbFuncao AS 
    SELECT 
    f.idFuncao AS ID,
    f.tipo AS funcao,
    p.nome_completo AS nome,
    f.email
    FROM funcao f
    inner join pessoa p ON p.idPessoa = f.idPessoa;
    
SELECT * FROM tbFuncao;

-- ------

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

-- ------

CREATE VIEW vw_funcao_pessoa AS
SELECT 
    f.idFuncao, 
    f.tipo, 
    f.email, 
    f.senha, 
    p.nome_completo
FROM funcao f
INNER JOIN pessoa p ON f.idPessoa = p.idPessoa;

-- ------

CREATE VIEW vw_funcao_pessoa2 AS
SELECT 
    f.idFuncao, 
    f.tipo, 
    f.email, 
    p.nome_completo
FROM funcao f
INNER JOIN pessoa p ON f.idPessoa = p.idPessoa
ORDER BY f.idFuncao;

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
-- TRIGGER

DELIMITER //
CREATE TRIGGER senha_Vadm
BEFORE INSERT 
ON funcao FOR EACH ROW 
BEGIN 
SET new.senha = UPPER(MD5(new.senha));
END
//
 -- --------------
DELIMITER //
CREATE TRIGGER senha_adm
BEFORE INSERT 
ON adm FOR EACH ROW 

BEGIN 
SET new.senha_adm = UPPER(MD5(new.senha_adm));
END
//
 -- -------------
DELIMITER //
CREATE TRIGGER senha_Vol
BEFORE INSERT 
ON voluntario FOR EACH ROW 

BEGIN 
SET new.senha_voluntario = UPPER(MD5(new.senha_voluntario));
END
//
