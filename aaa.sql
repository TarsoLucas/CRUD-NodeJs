USE webcfc_ba_giusoft;

SELECT * FROM geral_pessoas;

SELECT id, apelido, cpf_cnpj, renach_pago, nome 
FROM geral_pessoas;

describe geral_pessoas;
desc geral_pessoas;

INSERT INTO geral_pessoas (nome, apelido, senha, email, celular, telefone) 
VALUES ('aaa', 'aaa', 'aaa', 'aaa', 'aaa', 'aaa');

UPDATE geral_pessoas 
SET apelido = 'asdfasdf', cpf_cnpj = 12312, renach_pago = 123213 
WHERE id ='3435';

CREATE TABLE notas(
id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
numero BIGINT,
cliente varchar(50)
);

SELECT * FROM notas;

desc notas;

INSERT INTO notas (numero) VALUES (1);

DELETE FROM notas WHERE id = 19;

INSERT INTO geral_pessoas (apelido, cpf_cnpj, renach_pago, nome) VALUES ('d', 1, 2, 3);

INSERT INTO notas (numero) VALUES (4);

SELECT cliente, numero FROM notas;

UPDATE notas SET id =3460 WHERE id = 20;

SELECT geral_pessoas.id, geral_pessoas.apelido, geral_pessoas.cpf_cnpj, 
geral_pessoas.renach_pago, geral_pessoas.nome, notas.numero 
FROM geral_pessoas 
LEFT JOIN notas 
ON geral_pessoas.id=notas.id 
WHERE geral_pessoas.cpf_cnpj = 2;

SELECT geral_pessoas.id, geral_pessoas.cpf_cnpj, notas.numero 
FROM geral_pessoas 
LEFT JOIN notas 
ON geral_pessoas.id=notas.id 
WHERE geral_pessoas.cpf_cnpj = "2";

UPDATE geral_pessoas SET apelido = 'z', cpf_cnpj = 2, renach_pago = 3, nome = 4 WHERE id = 3465;

SELECT geral_pessoas.id, geral_pessoas.apelido, geral_pessoas.cpf_cnpj, 
geral_pessoas.renach_pago, geral_pessoas.nome, notas.numero 
FROM geral_pessoas 
LEFT JOIN notas 
ON geral_pessoas.id=notas.id 
WHERE geral_pessoas.cpf_cnpj = 2;

UPDATE notas SET numero = 012219512 WHERE id = 3465;

SELECT geral_pessoas.id, geral_pessoas.apelido, 
geral_pessoas.cpf_cnpj, geral_pessoas.renach_pago, geral_pessoas.nome, notas.numero 
FROM geral_pessoas 
LEFT JOIN notas 
ON geral_pessoas.id=notas.id 
WHERE geral_pessoas.apelido = 'z';

DELIMITER //
CREATE PROCEDURE combo_add_notas(IN nome varchar(50))
	BEGIN
		SELECT * FROM notas WHERE cliente = nome;
	END //

CALL combo_add_notas('z');

SELECT numero FROM notas WHERE cliente = 'z';