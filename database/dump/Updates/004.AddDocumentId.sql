
ALTER TABLE `user_bank_accounts`
  ADD `user_document_id` int(10) unsigned NULL AFTER `active`;
ALTER TABLE `user_bank_accounts`
  ADD INDEX `user_bank_accounts_user_document_id_foreign` (`user_document_id`);
ALTER TABLE `user_bank_accounts`
  ADD CONSTRAINT `fk_user_bank_accounts_user_document_id` FOREIGN KEY (`user_document_id`) REFERENCES `user_documentation`(`id`);