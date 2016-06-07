
ALTER TABLE `users`
  ADD `last_login_at` datetime NULL AFTER `currency`;
ALTER TABLE `users`
  ADD INDEX `users_last_login_at` (`last_login_at`);

UPDATE `users` SET `last_login_at` = (
	SELECT MAX( `created_at` ) 
	FROM `user_sessions`
	WHERE `user_id` = `users`.`id`
)