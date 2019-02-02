CREATE TABLE `user` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(80) NOT NULL,
  `name_full` VARCHAR(290) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `date_insert` DATETIME NOT NULL,
  `active` CHAR(1) DEFAULT 'y',
  `email` VARCHAR(250) UNIQUE NOT NULL,
  `last_login` DATETIME
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `listgeral` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `owner` BIGINT NOT NULL,
  `type` CHAR(1) DEFAULT '0',
  `date_created` DATETIME NOT NULL,
  `strength` FLOAT DEFAULT 0.3,
  `price` FLOAT DEFAULT NULL,
  FOREIGN KEY(`owner`) REFERENCES `empresa`(`id`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- apenas os sinals que nao serao mostrados para cada usuario
-- quando o sinal eh deletado de listgeral eh deletado automaticamente de listusernao
CREATE TABLE `listusernao` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `listgeral_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  FOREIGN KEY(`listgeral_id`) REFERENCES `listgeral`(`id`) ON DELETE CASCADE,
  FOREIGN KEY(`user_id`) REFERENCES `user`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `carteira` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `owner` BIGINT NOT NULL,
  `price_media` FLOAT NOT NULL,
  `amount` INT NOT NULL,
  FOREIGN KEY(`owner`) REFERENCES `empresa`(`id`),
  FOREIGN KEY(`user_id`) REFERENCES `user`(`id`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `listacompanhar` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `owner` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  FOREIGN KEY(`owner`) REFERENCES `empresa`(`id`),
  FOREIGN KEY(`user_id`) REFERENCES `user`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--- preparacao para seguinte view
CREATE VIEW `carteira_completa` AS
SELECT c.id, c.user_id, c.owner, c.price_media, c.amount, u.close ultimo, p.close penultimo  
FROM carteira as c,
(   SELECT `valor`.`owner`, `valor`.`close`, `valor`.`date` FROM `valor`,
    (SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1) a
    WHERE `valor`.`date` = a.`date`) as u,
(   SELECT `valor`.`owner`, `valor`.`close`, `valor`.`date` FROM `valor`,
    (SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1,1) b
    WHERE `valor`.`date`= b.`date`) as p
WHERE c.owner = p.owner and c.owner = u.owner;


-- ultimo
SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1;

-- penultimo
SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1,1;

-- criar uma view apartir da carteira e dos precos de fechameto
SELECT c.id, c.user_id, c.owner, c.price_media, c.amount, u.close ultimo, p.close penultimo  
FROM carteira as c,
(   SELECT `valor`.`owner`, `valor`.`close`, `valor`.`date` FROM `valor`,
    (SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1) a
    WHERE `valor`.`date` = a.`date`) as u,
(   SELECT `valor`.`owner`, `valor`.`close`, `valor`.`date` FROM `valor`,
    (SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1,1) b
    WHERE `valor`.`date`= b.`date`) as p
WHERE c.owner = p.owner and c.owner = u.owner;


---
--- traz fechamento de todos
SELECT `valor`.`owner`, `valor`.`close`, `valor`.`date` FROM `valor`,
(SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1) a
WHERE `valor`.`date` = a.`date`;

--- traz fechamento penultimo de todos
SELECT `valor`.`owner`, `valor`.`close`, `valor`.`date` FROM `valor`,
(SELECT * FROM `valor` WHERE owner = 29 ORDER BY `date` DESC limit 1,1) b
WHERE `valor`.`date`= b.`date`;


------ fim view