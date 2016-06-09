
ALTER TABLE `user_self_exclusions`
  ADD `staff_id` int(10) unsigned DEFAULT NULL AFTER `user_session_id`;
ALTER TABLE `user_self_exclusions`
  ADD `staff_session_id` int(10) unsigned DEFAULT NULL AFTER `staff_id`;

ALTER TABLE `user_self_exclusions`
  ADD CONSTRAINT `fk_user_self_exclusions_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
ALTER TABLE `user_self_exclusions`
  ADD CONSTRAINT `fk_user_self_exclusions_staff_session_id` FOREIGN KEY (`staff_session_id`) REFERENCES `staff_sessions` (`id`);

INSERT INTO `session_types` (`id`, `name`) VALUES
 ('self_exclusion.disabled', ''),
 ('self_exclusion.suspended', '');
 INSERT INTO `self_exclusion_types` (`id`, `name`, `priority`) VALUES 
 ('disabled', 'Disabled Account', '10'),
 ('suspended', 'Suspended Account', '11');

ALTER TABLE `user_statuses`
  ADD `disable_status_id` varchar(45) COLLATE utf8_general_ci DEFAULT NULL AFTER `selfexclusion_status_id`;
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_disable_status_id` FOREIGN KEY (`disable_status_id`) REFERENCES `self_exclusion_types` (`id`);