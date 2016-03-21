/* TABLES 

*/

CREATE TABLE `session_types` (
   `id` VARCHAR(100),
   `name` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `session_types` (`id`, `name`) VALUES ('login','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('logoff','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('sign_up','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('new_session','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('bet.received','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('new_game_session','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('uploaded_doc','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('uploaded_doc.comprovativo_identidade','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('uploaded_doc.comprovativo_iban','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('uploaded_doc.comprovativo_morada','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('self_exclusion','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('self_exclusion','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('self_exclusion','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('self_exclusion.cancel_revocation','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('','');
INSERT INTO `session_types` (`id`, `name`) VALUES ('','');



CREATE TABLE `professional_situation` (
  `id` varchar(2) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `professional_situation` (`id`, `name`) VALUES
('11', 'Trabalhador por conta própria'),
('22', 'Trabalhador por conta de outrem'),
('33', 'Profissional liberal'),
('44', 'Estudante'),
('55', 'Reformado'),
('66', 'Estagiário'),
('77', 'Sem atividade profissional'),
('88', 'Desempregado'),
('99', 'Outra');