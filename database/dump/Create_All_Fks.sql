/* Clean All FKs
SELECT concat('alter table ',table_schema,'.',table_name,' DROP FOREIGN KEY ',constraint_name,';')
FROM information_schema.table_constraints
WHERE constraint_type='FOREIGN KEY'
AND table_schema='ibetupco';
*/


/* Create All FKs

*/
/* users */
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_user_role_id` FOREIGN KEY (`user_role_id`) REFERENCES `user_roles` (`id`);
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_staff_session_id` FOREIGN KEY (`staff_session_id`) REFERENCES `staff_sessions` (`id`);

/* user_sessions */
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_user_sessions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
  /* -- Don't enforce untill we know all keys...
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_user_sessions_session_type` FOREIGN KEY (`session_types`) REFERENCES `users` (`id`);
  */ /* Rename to session_type_id*/

/* user_balances */
ALTER TABLE `user_balances`
  ADD CONSTRAINT `fk_user_balances_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_balances`
  ADD CONSTRAINT `fk_user_balances_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);

/* user_bank_accounts */
ALTER TABLE `user_bank_accounts`
  ADD CONSTRAINT `fk_user_bank_accounts_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_bank_accounts`
  ADD CONSTRAINT `fk_user_bank_accounts_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_bank_accounts`
  ADD CONSTRAINT `fk_user_bank_accounts_status_id` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

/* user_documentation */
ALTER TABLE `user_documentation`
  ADD CONSTRAINT `fk_user_documentation_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_documentation`
  ADD CONSTRAINT `fk_user_documentation_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_documentation`
  ADD CONSTRAINT `fk_user_documentation_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
ALTER TABLE `user_documentation`
  ADD CONSTRAINT `fk_user_documentation_staff_session_id` FOREIGN KEY (`staff_session_id`) REFERENCES `staff_sessions` (`id`);
ALTER TABLE `user_documentation`
  ADD CONSTRAINT `fk_user_documentation_status_id` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

/* user_limits */
ALTER TABLE `user_limits`
  ADD CONSTRAINT `fk_user_limits_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_limits`
  ADD CONSTRAINT `fk_user_limits_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_limits`
  ADD CONSTRAINT `fk_user_limits_limit_id` FOREIGN KEY (`limit_id`) REFERENCES `limit_types` (`id`);

/* user_profiles */
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_document_type_id` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`);
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_professional_situation` FOREIGN KEY (`professional_situation`) REFERENCES `professional_situation` (`id`);
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_nationality` FOREIGN KEY (`nationality`) REFERENCES `countries` (`cod_alf2`);
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_country` FOREIGN KEY (`country`) REFERENCES `countries` (`cod_alf2`);
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_staff_session_id` FOREIGN KEY (`staff_session_id`) REFERENCES `staff_sessions` (`id`);

/* user_self_exclusions */
ALTER TABLE `user_self_exclusions`
  ADD CONSTRAINT `fk_user_self_exclusions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_self_exclusions`
  ADD CONSTRAINT `fk_user_self_exclusions_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_self_exclusions`
  ADD CONSTRAINT `fk_user_self_exclusions_self_exclusion_type_id` FOREIGN KEY (`self_exclusion_type_id`) REFERENCES `self_exclusion_types` (`id`);
ALTER TABLE `user_self_exclusions`
  ADD CONSTRAINT `fk_user_self_exclusions_status_id` FOREIGN KEY (`status`) REFERENCES `statuses` (`id`);

/* user_revocations */
ALTER TABLE `user_revocations`
  ADD CONSTRAINT `fk_user_revocations_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_revocations`
  ADD CONSTRAINT `fk_user_revocations_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_revocations`
  ADD CONSTRAINT `fk_user_revocations_self_exclusion_id` FOREIGN KEY (`self_exclusion_id`) REFERENCES `user_self_exclusions` (`id`);
ALTER TABLE `user_revocations`
  ADD CONSTRAINT `fk_user_revocations_status_id` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);
  
/* user_statuses */
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_staff_session_id` FOREIGN KEY (`staff_session_id`) REFERENCES `staff_sessions` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_status_id` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_identity_status_id` FOREIGN KEY (`identity_status_id`) REFERENCES `statuses` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_email_status_id` FOREIGN KEY (`email_status_id`) REFERENCES `statuses` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_iban_status_id` FOREIGN KEY (`iban_status_id`) REFERENCES `statuses` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_address_status_id` FOREIGN KEY (`address_status_id`) REFERENCES `statuses` (`id`);
ALTER TABLE `user_statuses`
  ADD CONSTRAINT `fk_user_statuses_selfexclusion_status_id` FOREIGN KEY (`selfexclusion_status_id`) REFERENCES `self_exclusion_types` (`id`);

/* user_settings */
ALTER TABLE `user_settings`
  ADD CONSTRAINT `fk_user_settings_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_settings`
  ADD CONSTRAINT `fk_user_settings_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_settings`
  ADD CONSTRAINT `fk_user_settings_settings_type_id` FOREIGN KEY (`settings_type_id`) REFERENCES `settings` (`id`);

/* user_transactions */
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_user_session_id` FOREIGN KEY (`user_session_id`) REFERENCES `user_sessions` (`id`);
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_staff_session_id` FOREIGN KEY (`staff_session_id`) REFERENCES `staff_sessions` (`id`);
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_origin_transaction` FOREIGN KEY (`origin`) REFERENCES `transactions` (`id`);
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_user_bank_account_id` FOREIGN KEY (`user_bank_account_id`) REFERENCES `user_bank_accounts` (`id`);
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_status_id` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);
