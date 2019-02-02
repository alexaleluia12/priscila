status: pronto
    considera as emprejas cujos rsi foram calculados (view validos)
    deve ser rodados todos os dias logo apos a coleta do ultimo preco


- apaga tudo de listing
- pega o ultimo preco de todas as acoes
  configuracaos do ris para aquela acao
  calculo do rsi
  
  se valor_rsi <= compra
    insert into listing (id_acao, 'B')
  else if valor_rsi >= venda
    insert into listing (id_acao, 'S')


-- tabela --
CREATE TABLE `listing` (
  `owner` BIGINT NOT NULL,
  `type` CHAR(1) DEFAULT '0', -- B / S
  FOREIGN KEY(`owner`) REFERENCES `empresa`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
