ALTER TABLE `user_documentation` ADD `expire` DATETIME NULL DEFAULT NULL AFTER `status_id`;
INSERT INTO `statuses` (`id`, `name`, `bo_name`) VALUES ('pre-approved', 'Pre aprovado', 'Pre approved');