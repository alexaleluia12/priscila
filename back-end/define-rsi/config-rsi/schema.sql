
-- usuario e senha banco local
-- create user 'ufinanceiro'@'%' identified by 'kbx49';
-- create database auto_financeiro;
-- grant all privileges on auto_financeiro.* to  'ufinanceiro'@'%';

CREATE TABLE empresa (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `cod` VARCHAR(10) UNIQUE NOT NULL
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE valor (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `owner` BIGINT NOT NULL,
  `date` DATE NOT NULL,
  `open` FLOAT NOT NULL,
  `high` FLOAT NOT NULL,
  `low` FLOAT NOT NULL,
  `close` FLOAT NOT NULL,
  `volume` BIGINT NOT NULL,
  FOREIGN KEY(`owner`) REFERENCES `empresa`(`id`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `rsiconf` (
 `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
 `owner` BIGINT NOT NULL,
 `date_set` date NOT NULL,
 `season` int NOT NULL,
 `sell` int DEFAULT NULL,
 `buy` int DEFAULT NULL,--6
 FOREIGN KEY(`owner`) REFERENCES `empresa`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
