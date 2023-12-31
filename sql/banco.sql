CREATE DATABASE  IF NOT EXISTS `miniframeworkauth` ;
USE `miniframeworkauth`;

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
);
INSERT INTO `perfil`
(`id`,`nome`)VALUES(2,'admin');

DROP TABLE IF EXISTS `permissoes`;
CREATE TABLE `permissoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ;
INSERT INTO `permissoes`
(`id`,`nome`)VALUES(1,'index');

CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfilid` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `usuario_ibfk_1` (`perfilid`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`perfilid`) REFERENCES `perfil` (`id`)
) ;
DROP TABLE IF EXISTS `perfil_permissoes`;
CREATE TABLE `perfil_permissoes` (
  `perfilid` int NOT NULL,
  `permissao_id` int NOT NULL,
  PRIMARY KEY (`perfilid`,`permissao_id`),
  KEY `perfil_permissoes_ibfk_2` (`permissao_id`),
  CONSTRAINT `perfil_permissoes_ibfk_1` FOREIGN KEY (`perfilid`) REFERENCES `perfil` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `perfil_permissoes_ibfk_2` FOREIGN KEY (`permissao_id`) REFERENCES `permissoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO `perfil_permissoes`
(`perfilid`,`permissao_id`)VALUES(2,1);
DELIMITER $$

CREATE PROCEDURE `GetPermissoesPorPerfil` (IN perfilId INT)
BEGIN
    SELECT perm.nome 
    FROM permissoes perm
    JOIN perfil_permissoes pp ON perm.id = pp.permissao_id
    WHERE pp.perfilid = perfilId;
END$$

DELIMITER ;
